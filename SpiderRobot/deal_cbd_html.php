<?php
header('content_type;text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
require_once ('./collect.class.php');
require_once ('./phpQuery/phpQuery.php');
require_once ('./industrieArr.php');
// var_dump(page_Num('10','3'));exit();
$pdo= new PDO('mysql:host=localhost;dbname=bidinfo','root','Root123456',[PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8']);
try{
foreach ($industries as $indCode => $industrie) {//各个小行业循环indCode是行业编号，industrise是大行业类型
	$pageNum=page_Num($indCode,'1');//总爬取页数,第二参数是行业
	printf("this hangye:%s have page %d\n",$indCode,$pageNum);
	if($pageNum==0){
		printf("It isn't infomartion of industrie which %s\n",$indCode);
		sleep(3);
		continue;
	}	
  	for($i=1;$i<=$pageNum;$i++){//一个行业有多页信息
  		$one = new OpenUrlClass();
		$ScanfUrl='http://www.chinabidding.com/search/proj.htm';
		$one->_Seturl($ScanfUrl);
		$one->_Setcookie('JSESSIONID=657D40BC3807127A4B1CA97C19C6FAF4;');
		$postArr=[
		'fullText'=>'',
		'pubDate'=>'1',
		'infoClassCodes'=>'0105',
		'normIndustry'=>(string)$indCode,//行业类型
		'zoneCode'=>'',
		'fundSourceCodes'=>'',
		'poClass'=>'',
		'rangeType'=>'',
		'currentPage'=>$i
		];
		$one->_Setpost($postArr);
		$html=$one->curl_one_file();
		sleep(3);
		printf("OpenPage httpCode is %d\n",$one->headCode);
		$one = null;
		//将获取的HTML进行加工取数据
		phpQuery::newDocumentHTML($html,'utf-8');
		$doc =phpQuery::pq("#lab-show > div.as-floor-normal > div.span-f > div > ul > li");
		$insertStr='';//待插入数据库字符串
		foreach ($doc as $Row) {
			$b_title     =pq($Row)->find("span.txt")->text();
			$org_href 	 =pq($Row)->find("a.as-pager-item")->attr("href");
			$datetime 	 =pq($Row)->find("span.time")->text();						   
			$btime_begin =split_time($datetime);
			$b_stype     =pq($Row)->find("dl.horizontal>dd>span:eq(0)>strong")->text();
			$b_place     =pq($Row)->find("dl.horizontal>dd>span:eq(1)>strong")->text();
			$AgentCom 	 =pq($Row)->find("a>p")->text();	
			$agent_comp  =split_AgentCom($AgentCom);				   
			//末尾的字段是更新状态，其为2表示是来待加入详情表notice
			$tempStr=",('2','{$btime_begin}','{$b_place}',{$agent_comp},'{$org_href}','{$industrie}','{$b_stype}','{$b_title}','2')";
			$insertStr.=$tempStr;
			$tempStr='';
		}
		$insertStr=substr($insertStr,1);
		insert_Table($pdo,$insertStr,'bidinfo');
		// exit('<br/>某行业的第一页<br/>');
  	}
  	// exit('<br/>一个行业循环完<br/>');	
}
	$pdo = null;
	printf("\nAll industries had vivisted\n");
	require_once('deal_cbd_detail.php');
	printf("\nAll detail information collected\n");	
}catch(Exception $e){
	echo $e->getMessage();
}

/**
*分割那个代理公司
*/
function split_AgentCom($AgentCom){
	$pattern ='/受招标人委托/';
	$AgentCom_sp=preg_split($pattern,$AgentCom);
	if(strlen($AgentCom)==@strlen($AgentCom_sp[0])){
		$AgentCom='NULL';
	}else{
		$AgentCom=$AgentCom_sp[0];
	}
	return "'$AgentCom'";
}

/**
*分割那个时间
*/
function split_Time($str){
	$pattern = '/(\d+)-(\d+)-(\d+)/';
	preg_match($pattern, $str,$m);
	if(empty($m)){
		$datetime=null;
	}else{
		$datetime=$m[0];
	}
	return $datetime;
}

/**
*确定一共需要循环次数,该行业有多少页数据
*/
function page_Num($indCode,$timelong='1'){
	$onea = new OpenUrlClass();
	$ScanfUrl='http://www.chinabidding.com/search/proj.htm';
	$onea->_Seturl($ScanfUrl);
	$onea->_Setcookie('JSESSIONID=657D40BC3807127A4B1CA97C19C6FAF4;');
	$postArr=[
	'fullText'=>'',
	'pubDate'=>$timelong,//1为今天
	'infoClassCodes'=>'0105',
	'normIndustry'=>(string)$indCode,//行业类型
	'zoneCode'=>'',
	'fundSourceCodes'=>'',
	'poClass'=>'',
	'rangeType'=>'',
	'currentPage'=>'1'
	];
	$onea->_Setpost($postArr);
	$html=$onea->curl_one_file();
	$onea=null;
	//将获取的HTML进行加工取数据
	phpQuery::newDocumentHTML($html,'utf-8');
	$isempty   = phpQuery::pq("div.as-pager-none")->html();//判断行业信息,为空则没有行业信息，空为有行业信息
	if(!empty($isempty)){
		return 0;
	}else{
		$onlyApg   = phpQuery::pq("#pagerSubmitForm>a")->html();//判断行业信息页数，有>0，无为空且只有一页
		if(empty($onlyApg)){
			return 1;	
		}else{
			$pgDiv	   = phpQuery::pq("#pagerSubmitForm")->find("a");//a标签的数量
			$sumPg	   = phpQuery::pq("#pagerSubmitForm")->find("a:eq(".(count($pgDiv)-2).")")->text();//该行业的页数	
			return (int)$sumPg;
		}
	}
}

/**
*插入数据库bidinfo表
*/
function insert_Table($con,$sqlv,$table){
	$sql ="INSERT INTO `{$table}` (`fromsite`,`btime_begin`,`b_place`,`agent_comp`,`org_href`,`b_btype`,`b_stype`,`b_title`,`status`) VALUES ".$sqlv;
	$stmt= $con->prepare($sql);
	$res =$stmt->execute();
	return $res;
}