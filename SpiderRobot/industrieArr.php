<?php
$industries=[
'01'=>'1','03'=>'1','04'=>'1','06'=>'1','13'=>'1','14'=>'1','18'=>'1','20'=>'1','21'=>'1','23'=>'1','29'=>'1','30'=>'1','33'=>'1','42'=>'1',
'02'=>'2','07'=>'2','08'=>'2','09'=>'2','11'=>'2','16'=>'2','22'=>'2','24'=>'2','25'=>'2','37'=>'2','50'=>'2','10'=>'2',
'05'=>'3','12'=>'3','15'=>'3','17'=>'3','19'=>'3','26'=>'3','24'=>'3','28'=>'3','31'=>'3','32'=>'3','34'=>'3','35'=>'3','36'=>'3','38'=>'3',
'39'=>'3','40'=>'3','41'=>'3','43'=>'3','44'=>'3','45'=>'3','46'=>'3','47'=>'3','48'=>'3','49'=>'3',
];//值为 1-货物类 2-工程类 3-服务类
// var_dump($industries);exit();
// foreach ($industries as $indCode => $industrie) {
// 	echo '行业代码indCode:'.$indCode.' industrie: '.$industrie.'<br/>';
// }
/*
	$one = new OpenUrlClass();
	$ScanfUrl='http://www.chinabidding.com/search/proj.htm';
	$one->_Seturl($ScanfUrl);
	$one->_Setcookie('JSESSIONID=657D40BC3807127A4B1CA97C19C6FAF4;');
	$postArr=[
	'fullText'=>'',
	'pubDate'=>'3',
	'infoClassCodes'=>'0105',
	'normIndustry'=>$indCode,//行业类型
	'zoneCode'=>'',
	'fundSourceCodes'=>'',
	'poClass'=>'',
	'rangeType'=>'',
	'currentPage'=>'1'
	];
	$one->_Setpost($postArr);
	$html=$one->curl_one_file();
	//将获取的HTML进行加工取数据
	phpQuery::newDocumentHTML($html,'utf-8');
	// $isempty   = phpQuery::pq("div.as-pager-none")->html();//判断行业信息,有>0，无为空
	// $onlyApg   = phpQuery::pq("#pagerSubmitForm>a")->html();//判断行业信息页数，有>0，无为空且只有一页
	// $pgDiv	  = phpQuery::pq("#pagerSubmitForm")->find("a");//a标签的数量
	// $sumPg	  = phpQuery::pq("#pagerSubmitForm")->find("a:eq(".(count($pgDiv)-2).")")->text();//该行业的页数
	$doc 	   =phpQuery::pq("#lab-show > div.as-floor-normal > div.span-f > div > ul > li");//对象
	foreach ($doc as $i => $Row) {
		echo $title    =pq($Row)->find("span.txt")->text();							 echo '|<->|';
		     $datetime =pq($Row)->find("span.time")->text();						   
		echo split_time($datetime);
		echo '|<->|';
		echo $hangye   =pq($Row)->find("dl.horizontal>dd>span:eq(0)>strong")->text();echo '|<->|';
		echo $place    =pq($Row)->find("dl.horizontal>dd>span:eq(1)>strong")->text();echo '|<->|';
			 $AgentCom =pq($Row)->find("a>p")->text();	
		echo split_AgentCom($AgentCom);						   
		echo '<br/>';
	}
	exit();
*/
?>