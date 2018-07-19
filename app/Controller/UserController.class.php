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
			$res=$this->pdo->query("SELECT COUNT(*) AS `num` FROM `notice`");
			foreach ($res as $key => $value) {
				var_dump($value);
			}
			$response = $response->withStatus(200);
			$response->getBody()->write("hello,world!");
			return $response;
	}
}
?>