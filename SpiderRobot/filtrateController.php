<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;


class FiltrateController {
	
   protected $pdo;
   public function __construct(ContainerInterface $container){
   		$this->pdo=$container['db'];
   }

   /**
	*@param 用户登录
	*@return 返回数组
   	*/

      //信息类别筛选
   	public function fil(Request $request, Response $response){     //$request为大类别的代号
   		try{
            $request = $_GET['type'];//从前端传来的数据中得到大类别的代号
            $request_a = $_GET['place'];//地点
            $request_b = $_GET['time'];
            //如果返回4，则查询所有的数据
            if ($request == 4 ) {
               $sql = "select * from bidinfo";
            }
            else{
               $sql = "select * from bidinfo where b_btype = '{$request}'  ";
            }
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute();
            $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
            $t = 1;
            foreach ($res as  $value) {
               $content_btime_begin[] =array("btime_begin_{$t}"=>$value["btime_begin"],) ;
               $content_b_place[] = array("b_place_{$t}"=>$value["b_place"],) ;
               $content_agent_comp[] = array("agent_comp_{$t}"=>$value["agent_comp"],);
               $content_org_href[] = array("org_href_{$t}"=>$value["org_href"],);
               $content_b_stype[] = array("b_stype_{$t}"=>$value["b_stype"],);
               $content_b_btype[] = array("b_btype_{$t}"=>$value["b_btype"],);
               $content_b_detail[] = array("b_detail_{$t}"=>$value["b_detail"],);
               $content_b_title[] = array("b_title_{$t}"=>$value["b_title"],);
               $content_status[] = array("status_{$t}"=>$value["status"],);
               $content_bid[] = array("bid_{$t}"=>$value["bid"],);
               $t = $t + 1;
            }
            $arr_all_a = $this->jiaochaArray($content_btime_begin,$content_b_place,$content_b_title,$content_b_stype,$content_status,$content_bid);
            $arr_all_b = array_chunk($arr_all_a, 6);
            $i = 1;
            foreach ($arr_all_b as $value) {
               $alls[] = array("content_{$i}"=>$value,);
               $i = $i + 1;    
            }
            $c = json_encode(['content'=>$alls],JSON_UNESCAPED_UNICODE);
            $response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
            $response->getBody()->write(
               $c
            );
               return $response;
         }
         catch(Exception $e){
            $response = $response->withStatus($e->getCode())->withHeader('Content-type', 'application/json');
            $response->getBody()->write(json_encode(
               [
                     'errorCode' => $e->getCode(),
                     'error' => $e->getMessage()
               ]
               )
            );
         }
   	}


      //用户收藏
      public function collect(Request $request, Response $response){   //当用户选择收藏时$status为1,取消收藏的到时候$status为-1
         $openid = $_COOKIE['sessionId'];
         // $openid = 45;
         $status = $_GET['status'];
         $id = $_GET['id'];
         try{
            if($status == 1){
               $sql = "insert into collect (status,cbid,cuid) values ($status,$id,$openid)";
               $content = $this->pdo->exec($sql);
            }
            else{
               $sql = "update collect set status = $status where cuid = $openid and cbid = $id";
               $content = $this->pdo->exec($sql);
            }
         }
         catch(Exception $e){
            print $e->getMessage();
            exit();
         }
            $response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
            $response->getBody()->write(
               json_encode(
            [
             'msg'=>'收藏成功'
             ],
             JSON_UNESCAPED_UNICODE
            )
               );
               return $response;
      }


      //用户历史记录
      public function history(Request $request, Response $response){
         $openid = $_COOKIE['sessionId'];
         // $openid = 45;
         $id = $_GET['id'];
         $status = $_GET['status'];
         try{
            $sql = "insert into history (huid,hbid,status) values ($openid,$id,$status)";
            $content = $this->pdo->exec($sql);
         }
         catch(Exception $e){
            print $e->getMessage();
            exit();
         }
         $response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
         $response->getBody()->write(
              json_encode(
         [
            'msg'=>'历史记录储存成功'
          ],
          JSON_UNESCAPED_UNICODE
         )
              );
            return $response;
      }

      //个人中心的收藏与历史纪录的展示
      public function show(Request $request, Response $response){
         $openid = $_COOKIE['sessionId'];
         $classes = $_GET['classes'];
         try{
            if ($classes == "collect") {
               $sql = "select great_time,cbid from $classes where cuid = $openid and status = 1 ";
               // echo $sql;
               // echo "<br/>";
               $content = $this->pdo->query($sql);
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);//数组
               foreach ($res as $value) {
                  $content_id[] = $value["cbid"];//收藏的内容的序号
                  $content_time[] = $value["great_time"];//收藏的时间
               }
            }
            else if ($classes == "history") {
               $sql = "select histime,hbid from $classes where huid = $openid and status = 1 ";
               // echo $sql;
               // echo "<br/>";
               // $content = $this->pdo->query($sql);
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);//数组
               foreach ($res as $value) {
                  $content_id[] = $value["hbid"];//历史纪录的内容的序号
                  $content_time[] = $value["histime"];//历史纪录的时间
               }
            }
            else{
               echo "请选择收藏或者历史纪录";
            }
            // print_r($content_id);
            // exit();
            foreach ($content_id as $value) {
               $sql = "select b_title from bidinfo where bid = $value";
               // echo $sql;echo "<br/>";
               $content = $this->pdo->query($sql);
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);

               foreach ($res as $value) {
                  $content_title[] = $value["b_title"];//收藏或历史纪录的标题
               }
            }
            // print_r($content_title);
            // exit();
            foreach ($content_id as $value) {
               $sql ="select b_detail from notice where about_id = $value";
               $content = $this->pdo->query($sql);
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
               foreach ($res as $value) {
                  $content_details[] = $value["b_detail"];//收藏或历史纪录的内容
               }
            }
            // print_r($content_details);
            // exit();
            $response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
            $response->getBody()->write(
                 json_encode(
                     [
                        'time'=>$content_time,
                        'title'=>$content_title,
                        'content'=>$content_details
                      ],
                      JSON_UNESCAPED_UNICODE
                     )
                 );
            // echo $response;
               return $response;
         }
         catch(Exception $e){
            print $e->getMessage();
            exit();
         }
      }
   	}

?>