<?php
header('content_type;text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
require_once ('./collect.class.php');
require_once('./phpQuery/phpQuery.php');
$ScanfUrl='http://search.ccgp.gov.cn/bxsearch?searchtype=1&page_index=1&dbselect=bidx&bidSort=0&pinMu=2&bidType=1&kw=&start_time=2018%3A07%3A06&end_time=2018%3A07%3A13&timeType=2';

$one = new OpenUrlClass();
$one-> _Seturl($ScanfUrl);
$html=$one->curl_one_file();

//将获取的HTML进行加工取数据
sleep(3);
$document   = phpQuery::newDocumentHTML($html,'utf-8');
//因为这个对象可以有读取节点相应信息的方法，如果find   html。text 等方法；
$doc        = phpQuery::pq("");//对象
$ulRows  = $doc->find("ul.vT-srch-result-list-bid>li");
foreach ($ulRows as $key => $ulRow) {
	echo $title=pq($ulRow)->find("a:eq(0)")->text();			echo '@';//招标标题
	echo $title_url=pq($ulRow)->find("a:eq(0)")->attr("href");	echo '@';//详情链接
	echo $place=pq($ulRow)->find("a:eq(1)")->text();			echo '@';//省份
	echo $type =pq($ulRow)->find("strong:eq(1)")->text();				 //类别
	echo '<br/>';
}