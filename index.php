<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
date_default_timezone_set("PRC");
require_once('./vendor/autoload.php');//composer加载slim框架
require_once('./app/libs/config/dbconf.php');//数据库配置
require_once('./app/Controller/UserController.class.php');
require_once('./app/Controller/SearchController.class.php');

$app = new \Slim\App(["settings" => $config]);//数据库配置数组$config
$container = $app->getContainer();//这是一个PDO容器'db'用来装资源句柄
$container['db'] = function ($config) {
    $db = $config['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
    	$db['user'], $db['pass'],[PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->getAttribute(PDO::ATTR_SERVER_INFO);
    return $pdo;
};
$app->post('/onlogin','\UserController:onlogin');//路由,使用控制器类中的方法,登录
$app->post('/searchbid','\SearchController:searchbid');//模糊搜索
$app->get('/agent_company_rank','\SearchController:agent_company_rank');//代理公司的排名
$app->get('/searchbid_views_rank','\SearchController:searchbid_views_rank');//信息热度排名
$app->post('/recommend','\SearchController:recommend');//推荐信息
$app->get('/search_detail','\SearchController:search_detail');//详情信息
$app->run();//启动监听函数
?>
