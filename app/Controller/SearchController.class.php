<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;


class SearchController {
	
   protected $pdo;
   public function __construct(ContainerInterface $container){
   		$this->pdo=$container['db'];
   }

   /**
	*@param 用户搜索功能
	*@return 返回数组
	*@param 前端传送 {"searhword":"小明@搜索@机械","startpage":"0"}
   	*/
   public function searchbid (Request $request, Response $response){
		try{
			$postDatas = $request->getParsedBody();
			$searhword = self::SpiltWord($postDatas['searhword']);
			$sql = self::KeywordSql($searhword,(int)$postDatas['startpage']*20,20);
			$res = self::SearchAllInfo($this->pdo,$sql);
			// var_dump($res);exit();
			//执行了查询语句开始返回
			$response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
			$response->getBody()->write(json_encode(
				[
				 	'statusCode' => 'ok',
		            'msg' => $res
				]
			));
			return $response;
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
	*@param 代理机构排名
	*@return 返回数组
   	*/
   public function agent_company_rank(Request $request, Response $response){
	   try{
		   	$sql = "SELECT *FROM `agent_com_rank` WHERE `agent_comp`!='NULL' LIMIT 10";
		   	$res = self::SearchAllInfo($this->pdo,$sql);//只是执行查询函数
		   	//执行了查询语句开始返回
			$response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
			$response->getBody()->write(json_encode(
				[
				 	'statusCode' => 'ok',
		            'msg' => $res
				]
			));
			return $response;
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
   *@param String 正则匹配分割用户输入
   *@return Array
   */
   private function SpiltWord($inputword){
     $pattern='/(\s+)|[~@#$%^&*(){};:\',.\/?]+/i';
     $arr=preg_split($pattern, $inputword);
     return $arr;
   }
   private function KeywordSql($keyword,$start,$lit){
   	  $tail=" ORDER BY `btime_begin` DESC LIMIT {$start},{$lit}";
   	  if(is_array($keyword)){
   	  		$sql2='';
   	  		foreach ($keyword as $key => $value) {
   	  			if($key==0){
				$sql2.='('."SELECT `bid`,`b_title`,`b_stype`,`agent_comp`,`b_place`,`btime_begin` FROM `bidinfo` WHERE ( `b_title` LIKE '%{$value}%' OR `b_stype` LIKE '%{$value}%' OR `agent_comp` LIKE '%{$value}%' OR `b_place` LIKE '%{$value}%' ) AND `status`=1".')';
   	  			}else{
   	  			$sql2.=" UNION ".'('."SELECT `bid`,`b_title`,`b_stype`,`agent_comp`,`b_place`,`btime_begin` FROM `bidinfo` WHERE ( `b_title` LIKE '%{$value}%' OR `b_stype` LIKE '%{$value}%' OR `agent_comp` LIKE '%{$value}%' OR `b_place` LIKE '%{$value}%' ) AND `status`=1".')';
   	  			}
   	  		}
   	  		return $sql2.$tail;
   	  }else{
   	  	$sql1="SELECT `bid`,`b_title`,`b_stype`,`agent_comp`,`b_place`,`btime_begin` FROM `bidinfo` 
			WHERE ( `b_title` LIKE '%{$keyword}%'
			OR `b_stype` LIKE '%{$keyword}%'
			OR `agent_comp` LIKE '%{$keyword}%' 
			OR `b_place` LIKE '%{$keyword}%' ) AND `status`=1";
			return $sql1.$tail;
   	  }
   }
   private function SearchAllInfo($pdo,$sql){
		$stmt=$pdo->prepare($sql);
		$stmt->execute();
		$res =$stmt->fetchAll($pdo::FETCH_ASSOC);
		return $res;
   }

}
?>