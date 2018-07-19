<?php
$str='重庆招标采购（集团）有限责任公司受招标人委托对下列产品及服务进行国际公开竞争性招标，于****-*...';
$str='发布时间：';
$str='湖北省-襄阳市';
// $pattern='/受招标人委托/';
// $m=preg_split($pattern,$str);var_dump($m);
// $pattern = '/(\d+)-(\d+)-(\d+)/';
$pattern='/-/';
$m=preg_split($pattern,$str);var_dump($m);exit();
$a=preg_match($pattern, $str,$m);
var_dump($a);
var_dump($m);
exit();
