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
	*@param 用户登录
	*@return 返回数组
   	*/
   public function searchbid (Request $request, Response $response){
		try{
			
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

}
?>