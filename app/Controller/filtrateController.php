<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;
 error_reporting(0);

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
         $sql = "select cbid from collect where status = 1 ";
         $content = $this->pdo->query($sql);
         $stmt=$this->pdo->prepare($sql);
         $stmt->execute();
         $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
         foreach ($res as $value) {
            $collect_id[] = $value['cbid'];
         }
         $ollect_id_c[] = array('id'=>$collect_id);
         try{
            $request = $_GET['type'];//从前端传来的数据中得到大类别的代号
            $request_a = $_GET['place'];//地点
            $request_b = $_GET['time'];//时间段
            $request_c = $_GET['ye'];//分页
            $time_a = time() - 86400*$request_b;
            $time_b = date("Y-m-d H:i:s",$time_a);
            $request_e = ($request_c - 1)*20;
            $request_f = $request_c*20;
            $openid = $_COOKIE['sessionId']; //用户的id信息

            // $collect_status = $_GET['status'];//如果要取消收藏则为2，否则为1
            // $openid = $_COOKIE['sessionId'];//确定改动收藏信息的用户
            // $openid = 45;
            // if ($collect_status == 2) {
            //    echo "已经删除了这条信息！";
            //    echo "<br/>";
            //     // $sql = "update collect set status = $collect_status where cuid = $openid";
            //     // $this->pdo->query($sql);
            //     // return $collect_status;
            //  } 
            //如果返回4，则查询所有的数据
            if ($request == 4) {
               if ($request_a !== a) {
                  if ($request_b !== a) {
                     $sql = "select * from bidinfo where status = 1 and b_place like '%{$request_a}%' and btime_begin > '{$time_b}'  limit $request_e,$request_f";
                  }
                  else{
                     $sql = "select * from bidinfo where status = 1 and b_place like '%{$request_a}%' limit $request_e,$request_f";
                  }
                  
               }
               else{
                  if ($request_b !== a) {
                     $sql = "select * from bidinfo where status = 1 and btime_begin > '{$time_b}' limit $request_e,$request_f";
                  }
                  else{
                     $sql = "select * from bidinfo where status = 1 limit $request_e,$request_f";
                  }
               }
            }
            else{
               if ($request_a !== a) {
                  if ($request_b !== a) {
                     $sql = "select * from bidinfo where b_btype = '{$request}' and b_place like '%{$request_a}%' and btime_begin > '{$time_b}' limit $request_e,$request_f";
                  }
                  else{
                     $sql = "select * from bidinfo where b_btype = '{$request}' and b_place like '%{$request_a}%' limit $request_e,$request_f";
                  }
               }
               else{
                  if ($request_b !== a) {
                     $sql = "select * from bidinfo where b_btype = '{$request}' and btime_begin > '{$time_b}' limit $request_e,$request_f";
                  }
                  else{
                     $sql = "select * from bidinfo where b_btype = '{$request}' limit $request_e,$request_f ";
                  }
               }
            }
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute();
            $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
            $t = 1;
            foreach ($res as  $value) {
               $time_c = $value["btime_begin"];
               $arr = explode(" ",$time_c);
               $time_d = $arr[0];
               $content_btime_begin[] =array("btime_begin_{$t}"=>$time_d,) ;
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
            //获取收藏信息的状态
            if ($openid == null) {
               $r = 1;
               foreach ($content_bid as $value) {
                  $content_id[] = array("status_id_{$r}"=>0);
                  $r = $r + 1;
               }
            }
            else{
               $r = 1;
               foreach ($content_bid as $value) {
                  $sql = "select status from collect where cbid = ".$value["bid_{$r}"]." and cuid = $openid";
                  // echo $sql;
                  // echo "<br/>";
                  $content = $this->pdo->query($sql);
                  $stmt=$this->pdo->prepare($sql);
                  $stmt->execute();
                  $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
                  $content_id[] =array("status_id_{$r}"=>$res[0][status]);
                  $r = $r +1;
               }
            }
            
            // print_r($content_id);
            // echo "<br/>";
            // print_r($content_btime_begin);
            // exit();
            $arr_all_a = $this->jiaochaArray($content_btime_begin,$content_b_place,$content_b_title,$content_b_stype,$content_id,$content_bid,$content_agent_comp);
            $arr_all_b = array_chunk($arr_all_a, 7);
            $i = 1;
            foreach ($arr_all_b as $value) {
               // echo $value[6]["agent_comp_1"];
               // print_r($value);
               // exit();
               $alls[] = array("btime_begin"=>$value[0]["btime_begin_{$i}"],"b_place"=>$value[1]["b_place_{$i}"],"b_title"=>$value[2]["b_title_{$i}"],"b_stype"=>$value[3]["b_stype_{$i}"],"status"=>$value[4]["status_id_{$i}"],"bid"=>$value[5]["bid_{$i}"],"agent_comp"=>$value[6]["agent_comp_{$i}"]);
               $i = $i + 1;    
            }
            // print_r($alls);
            // exit();
            $c = json_encode($alls,JSON_UNESCAPED_UNICODE);
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
             'msg'=>'执行成功'
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
            if ($status == 1) {
               $sql = "insert into history (huid,hbid,status) values ($openid,$id,$status)";
               $content = $this->pdo->exec($sql);
            }
            else{
               $sql = "update history set status = $status where huid = $openid and hisid = $id";
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
         //$openid = 1;
         $classes = $_GET['classes'];//有两种collect和history
         $request_c = $_GET['ye'];//分页
         $inter = $_GET['inter'];//当这个变量为a时，则为需要某条的具体内容
         $content_id_a = $_GET['id'];//需要具体信息的id
         $request_e = ($request_c - 1)*20;
         $request_f = $request_c*20;
         if ($inter == "a") {
            $sql ="select b_detail from notice where about_id = $content_id_a";
            $content = $this->pdo->query($sql);
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute();
            $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
            $content_details = $res[0]["b_detail"];
            // foreach ($res as $value) {
            //    $content_details = $value["b_detail"];//收藏或历史纪录的内容
            // }
            $c = json_encode(['content'=>$content_details],JSON_UNESCAPED_UNICODE);
            $response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
            $response->getBody()->write(
                    $c
                 );
            return $response;
         }
         try{
            if ($classes == "collect") {
               $sql = "select great_time,cbid from $classes where cuid = $openid and status = 1 order by collid desc limit $request_e,$request_f";
               $content = $this->pdo->query($sql);
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);//数组
               foreach ($res as $value) {
                  $content_id[] = array('cbid'=>$value["cbid"]);//收藏的内容的序号
                  $content_time[] = $value["great_time"];//收藏的时间
               }
            }
            else if ($classes == "history") {
               $sql = "select histime,hbid,hisid from $classes where huid = $openid and status = 1 order by hisid desc limit $request_e,$request_f";
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);//数组
               foreach ($res as $value) {
                  $content_id[] = array('hbid'=>$value["hbid"],);//历史纪录的内容的序号
                  $content_hisid[] = array('hisid' => $value["hisid"],);//历史纪录的唯一编号
                  $content_time[] = $value["histime"];//历史纪录的时间
               }
            }
            else{
               echo "请选择收藏或者历史纪录";
            }
            // print_r($content_time);
            // exit();
            foreach ($content_id as $value) {
               if (isset($value['hbid'])) {
                  $content_value = $value["hbid"];
               }
               else{
                  $content_value = $value["cbid"];
               }
               $sql = "select b_title,b_stype,b_place,status,btime_begin,agent_comp from bidinfo where bid = {$content_value}";
               $content = $this->pdo->query($sql);
               $stmt=$this->pdo->prepare($sql);
               $stmt->execute();
               $res =$stmt->fetchAll(PDO::FETCH_ASSOC);
               $t = 1;
               foreach ($res as $value) {
	$time_c = $value["btime_begin"];
                  $arr = explode(" ",$time_c);
                  $time_d = $arr[0];
                  $content_title[] = array("b_title_{$t}" =>$value["b_title"] , );//收藏或历史纪录的标题
                  $content_type[] = array("b_stype_{$t}"=>$value["b_stype"],);
                  $content_place[] = array("b_place_{$t}"=>$value['b_place'],);
                  $content_status[] = array("status_{$t}"=>$value['status'],);
                  $content_btime_begin[] =array("btime_begin_{$t}"=>$time_d,) ;
                  $content_agent_comp[] = array("agent_comp_{$t}"=>$value["agent_comp"],);
                  $t = $t + 1;
               }
            }
            // print_r($content_id);//二维数组
            // exit();
            foreach ($content_id as $value) {
               if (isset($value['hbid'])) {
                  $content_value = $value["hbid"];
                  $content_value_a = "hbid";
               }
               else{
                  $content_value = $value["cbid"];
                  $content_value_a = "cbid";
               }
               $content_ids[] = $content_value;
            }
            // print_r($content_hisid);
            if ($content_id[0][cbid] === null) {
               $arr_all_a = $this->jiaochaArray1($content_title,$content_type,$content_place,$content_status,$content_btime_begin,$content_id,$content_hisid,$content_agent_comp);
               $arr_all_b = array_chunk($arr_all_a, 8);
                // exit();
                // $i = 1;
              // echo "<br/>";
               foreach ($arr_all_b as $value) {
                  // echo $content_value;
                  // exit();
                  $alls[] = array("b_title"=>$value[0]['b_title_1'],"b_stype"=>$value[1]['b_stype_1'],"b_place"=>$value[2]['b_place_1'],"collect_sign"=>$value[3]['status_1'],"btime_begin"=>$value[4]['btime_begin_1'],"bid"=>$value[5]["$content_value_a"],"hisid"=>$value[6]["hisid"],"agent_comp"=>$value[7]["agent_comp_1"]);
                  // $i = $i + 1;    
               }
            }
            else{
               $arr_all_a = $this->jiaochaArray($content_title,$content_type,$content_place,$content_status,$content_btime_begin,$content_id,$content_agent_comp);
               $arr_all_b = array_chunk($arr_all_a, 7);
               // print_r($arr_all_b);
               // exit();
               // $i = 1;
               foreach ($arr_all_b as $value) {
                  // echo $content_value;
                  // exit();
                  $alls[] = array("b_title"=>$value[0]['b_title_1'],"b_stype"=>$value[1]['b_stype_1'],"b_place"=>$value[2]['b_place_1'],"collect_sign"=>$value[3]['status_1'],"btime_begin"=>$value[4]['btime_begin_1'],"bid"=>$value[5]["$content_value_a"],"agent_comp"=>$value[6]["agent_comp_1"]);
                  // $i = $i + 1;    
               }
            }
            $c = json_encode(['ID'=>$content_ids,'content'=>$alls],JSON_UNESCAPED_UNICODE);
            $response = $response->withStatus(200)->withHeader('Content-type', 'application/json');
            $response->getBody()->write(
                    $c
                 );
            return $response;
         }
         catch(Exception $e){
            print $e->getMessage();
            exit();
         }
      }



      //多数组进行交叉组合成一个数组
      public function jiaochaArray($arr1, $arr2, $arr3, $arr4, $arr5, $arr6,$arr7)
         {
            $arr1 = array_values($arr1);
            $arr2 = array_values($arr2);
            $arr3 = array_values($arr3);
            $arr4 = array_values($arr4);
            $arr5 = array_values($arr5);
            $arr6 = array_values($arr6);
            $arr7 = array_values($arr7);
            $count = max(count($arr1), count($arr2), count($arr3), count($arr4), count($arr5), count($arr6), count($arr7));
            $arr = array();
            for ($i = 0; $i < $count; $i++) {
                  if ($i < count($arr1)) $arr[] = $arr1[$i];
                  if ($i < count($arr2)) $arr[] = $arr2[$i];
                  if ($i < count($arr3)) $arr[] = $arr3[$i];
                  if ($i < count($arr4)) $arr[] = $arr4[$i];
                  if ($i < count($arr5)) $arr[] = $arr5[$i];
                  if ($i < count($arr6)) $arr[] = $arr6[$i];
                  if ($i < count($arr7)) $arr[] = $arr7[$i];
               }
            return $arr;
         }

   public function jiaochaArray1($arr1, $arr2, $arr3, $arr4, $arr5, $arr6,$arr7,$arr8)
         {
            $arr1 = array_values($arr1);
            $arr2 = array_values($arr2);
            $arr3 = array_values($arr3);
            $arr4 = array_values($arr4);
            $arr5 = array_values($arr5);
            $arr6 = array_values($arr6);
            $arr7 = array_values($arr7);
            $arr8 = array_values($arr8);
            $count = max(count($arr1), count($arr2), count($arr3), count($arr4), count($arr5), count($arr6), count($arr7), count($arr8));
            $arr = array();
            for ($i = 0; $i < $count; $i++) {
                  if ($i < count($arr1)) $arr[] = $arr1[$i];
                  if ($i < count($arr2)) $arr[] = $arr2[$i];
                  if ($i < count($arr3)) $arr[] = $arr3[$i];
                  if ($i < count($arr4)) $arr[] = $arr4[$i];
                  if ($i < count($arr5)) $arr[] = $arr5[$i];
                  if ($i < count($arr6)) $arr[] = $arr6[$i];
                  if ($i < count($arr7)) $arr[] = $arr7[$i];
                  if ($i < count($arr8)) $arr[] = $arr8[$i];
               }
            return $arr;
         }
   }
?>