<?php
header('content_type;text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
require_once('./phpQuery/phpQuery.php');
require_once ('./collect.class.php');
$pdo = new PDO('mysql:host=localhost;dbname=bidinfo','root','',[PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8']);
$Rows= $pdo->query("SELECT `bid`,`org_href` FROM `bidinfo` WHERE status=2 AND fromsite=2",PDO::FETCH_ASSOC);
$one = new OpenUrlClass();
$one->_Setcookie('JSESSIONID=657D40BC3807127A4B1CA97C19C6FAF4;');
foreach ($Rows as $Row) {//Rows二维结果集,其元组属性为bid和org_href
	$one->_Seturl($Row['org_href']);	
	$html=$one->curl_one_file();
	sleep(3);
	phpQuery::newDocumentHTML($html,'utf-8');
	$doc =phpQuery::pq("div.as-article-body.table-article")->html();
	$doc =htmlspecialchars($doc, ENT_QUOTES);//htmlspecialchars_decode()
	//将$doc插入到notice表中
	$rs=insert_detail_notice($pdo,$doc,$Row['bid']);
	if($rs){
	//将此bid对应bidinfo表的status更改为1
	update_bidinfo_status($pdo,$Row['bid']);	
	}
	printf("Insert into notice:%d,This is the No.%d\r\n",(int)$rs,(int)$Row['bid']);
}
$one=null;
$pdo=null;
/**
*@return 插入表notice返回影响结果行数
*/
function insert_detail_notice($pdo,$html,$bid) {
	$sql="INSERT INTO `notice` (`about_id`,`b_detail`) VALUES ('{$bid}','{$html}')";
	$res=$pdo->exec($sql);
	return $res;
}
/**
*@return 更改表bidinfo对应值返回结果行数
*/
function update_bidinfo_status($pdo,$bid) {
	$sql="UPDATE `bidinfo` SET status=1 WHERE bid={$bid}";
	$res=$pdo->exec($sql);
	return $res;
}

?>