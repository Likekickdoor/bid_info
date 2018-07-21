<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

class UserController {

   protected $pdo;
   public function __construct(ContainerInterface $container){
   		$this->pdo=$container['db'];
   }

   public function onlogin (Request $request, Response $response){
		try{
			//http头部Cookie属性有值为sessionId=xxxxx，说明拥是老用户并且还是在会话中的
			if(!empty($_COOKIE['sessionId'])){
				//echo '有sessionId:'.$_COOKIE['sessionId'].'欢迎';exit;
				//！！后期还需完善验证sessionId的合法性
				$response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
				$response->getBody()->write(json_encode(
					[
					 'sessionId'=>'-1',
					 'statusCode'=>'ok',
					 'msg'=>'You have logined',
					]
				));
				return $response;
			}else{
				//没有sessionId，即用户可能是 --Cookie过期了-- 或者是 --新的用户-- 参数里面一定就有带有 <微信发的code> ，这前端来决定

				//1.解析微信的code，得到openid，并在数据库中查询是《新用户》还是《老用户》
				$postDatas = $request->getParsedBody();//var_dump($postDatas);exit;
				$code=@$postDatas['code'];//前端小程序传来的参数，应该会有code、username、password
				$wx_rep=self::getUserSigne($code);//微信得到的用户openid、session_key等
				$sql="SELECT `uid` FROM user WHERE `openid` = '{$wx_rep['openid']}' ";
				$SqlRes=self::executeSql($this->pdo,$sql);//$res['uid']==1该微信号已经绑定业务不是新用户

				//2.微信判断新、老用户实行不同处理逻辑
				if(!$SqlRes['uid']){//2.1是新用户，添加入库，返回sessionId					
					$dateArr=[
					'UserName'=>$postDatas['UserName'],
					'openid'  =>$wx_rep['openid'],
					'face'    =>$postDatas['face'],
					'u_place' =>$postDatas['u_place'],
					'u_ind_type' =>$postDatas['u_ind_type'],
					'u_agent'   =>$postDatas['u_agent'],
					];
					$sql=self::addUserSql($dateArr);
					$SqlRes=$this->pdo->exec($sql);
					if(!$SqlRes){
						throw new Exception("add fail,MySQL error", 500);
					}
					$uid   =$this->pdo->lastInsertId();//插入users表并得到uid-->sessionId
					$response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
					$response->getBody()->write(json_encode(
						[
					    'sessionId'=>$uid,
					    'statusCode'=>'ok',
					    'msg'=>'login successful!New member'
					    ]
					));
							//setcookie("sessionId",$uid,60);
						return $response;
					}else{//2.2是老用户,但是cookie已经过期了
						$uid=$SqlRes['uid'];
						$response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
						$response->getBody()->write(json_encode(
						[
					    'sessionId'=>$uid,
					    'statusCode'=>'ok',
					    'msg'=>'login successful!Old member'
					    ]
						));
							//setcookie("sessionId",$uid,60);
							return $response;
					}
				
				}
		}catch(Exception $e){
			$response = $response->withStatus($e->getCode())->withHeader('Content-type', 'application/json');
			$response->getBody()->write(json_encode(
				[
		            'errorCode' => $e->getCode(),
		            'error' => $e->getMessage()
		        ]
			));
			return $response;
		}
   }

   /**
	*@param 去微信申请到用户的识别码并存库
	*@return 返回数组
   	*/
	private function getUserSigne($code){
		$AppId='wx1192ff5063f47b6a';//招标小程序AppI
		$AppSecret='bbf53b14490d00914f3a91ee94ef1217';//招标小程序AppS
		$url="https://api.weixin.qq.com/sns/jscode2session?appid={$AppId}&secret={$AppSecret}&js_code={$code}&grant_type=authorization_code";
		// $url='./wxUser.json';
		$data=file_get_contents($url);//'wxUser.json'$url
		$data=json_decode($data,true);
		return $data;
	}
	private function executeSql($pdo,$sql){
		$stmt=$pdo->prepare($sql);
		$stmt->execute();
		$res =$stmt->fetch($pdo::FETCH_ASSOC);
		return $res;
	}

	private function addUserSql($dateArr){
		if(empty($dateArr['UserName'])||empty($dateArr['openid'])||empty($dateArr['face'])){
			throw new Exception("Error!,lost some param", 400);
		}
		if(empty($dateArr['u_place'])){
			$dateArr['u_place']='DEFAULT';
		}
		if(empty($dateArr['u_ind_type'])){
			$dateArr['u_ind_type']='DEFAULT';
		}
		if(empty($dateArr['u_agent'])){
			$dateArr['u_agent']='DEFAULT';
		}
		$sql="INSERT INTO user (`username`,`openid`,`face`,`u_place`,`u_ind_type`,`u_agent`)
	    VALUES ('{$dateArr['UserName']}','{$dateArr['openid']}','{$dateArr['face']}','{$dateArr['u_place']}','{$dateArr['u_ind_type']}','{$dateArr['u_agent']}')";
	    return $sql;
	}

}
?>