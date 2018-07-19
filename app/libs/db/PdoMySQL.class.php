<?php
class PdoMySQL{
    
    public static $config=array();//设置连接参数，配置信息
    public static $link=null;//保存数据库连接的--标识符
    public static $pconnect=false;//是否开启长连接
	public static $dbVersion=null;//保存数据库版本
	public static $connected=false;//是否连接成功
    public static $PDOStatement=null;//保存PDOStaement对象
    public static $queryStr=null;//保存最后执行的操作
    public static $error=null;//保存错误信息
    public static $lastInsertId=null;//保存上一步插入操作产生的AUTO_INCREMENT
    public static $numRows=0;//上一步操作产生受影响的记录的条数

	public function __construct($dbConfig=''){
		if(!class_exists("PDO")){
			self::throw_excption('不支持PDO,请先开启');
		}

		if(!is_array($dbConfig)){
			$dbConfig=array(
            'hostname'=>DB_HOST,
            'username'=>DB_USER,
            'password'=>DB_PWD,
            'database'=>DB_NAME,
            'hostname'=>DB_PORT,
            'dbms'=>DB_TYPE,
            'dsn'=>DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME
			);
		}
		if(empty($dbConfig['hostname']))
			self::throw_excption('没有定义数据库配置，');
		    self::$config=$dbConfig;
	    if(empty(self::$config['params']))
            self::$config['params']=array();
        if(!isset(self::$link)){
            $configs=self::$config;
            if(self::$pconnect){
            	//开启长连接，添加到配置数组中
                $configs['params'][constant("PDO::ATTR_PERSISTENT")]=true;
            }
            try{
               self::$link=new PDO($configs['dsn'],$configs['username'],$configs['password'],$configs['params']);
            }catch(PDOException $e){
               self::throw_excption($e->getMassage());
            }
            if(!self::$link){
            	self::throw_excption('PDO连接失败');
            	return false;
            }
            self::$link->exec('SET NAMES '.DB_CHARSET);
            self::$dbVersion=self::$link->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
            self::$connected=true;
            unset($configs);
        }
 	}
    

    /**
    *得到所有记录
    *@param string $sql
    *@param unknown
    */
    public static function getAll($sql=null){
    	if($sql!=null){
    		self::query($sql);
    	}
    	$result=self::$PDOStatement->fetchAll(constant("PDO::FETCH_ASSOC"));
    	return $result;
    }
    

    /**
     *得到一条记录
    *@param string $sql
    *@param unknown
    */
    public static function getRow($sql=null){
    	if($sql!=null){
    		self::query($sql);
    	}
    	$result=self::$PDOStatement->fetch(constant("PDO::FETCH_ASSOC"));
    	return $result;
    }
    

    /**
    *根据主键查找记录仪
    *@param string $tabName
    *@param int $priId
    *@param string $fields
    *@return mixed 
    */
    public static function findById($tabName,$priId,$fields='*'){
        $sql="SELECT %s FROM %s WHERE id=%d";
        return self::getRow(sprintf($sql,self::parseFields($fields),$tabName,$priId));
    }
    
    /**
    *
    */
    public static function find($tables,$where=null,$fields='*',$group=null,$having=null,$order=null,$limit=null){
    	$sql='SELECT '.self::parseFields($fields).' FROM '.$tables.' '
    	.self::parseWhere($where)
    	.self::parseGroup($group)
    	.self::parseHaving($having)
    	.self::parseOrder($order)
    	.self::parseLimit($limit);
    	// echo $sql;exit();
    	$dataALL=self::getAll($sql);
    	return count($dataALL)==1?$dataALL[0]:$dataALL;
    }
    
    /**
    *添加记录的操作
    *@param array $data
    *@param string $table
    INSERT into user(username,password,email)
    VALUES('aa','aa','aa@qq.com')
    */
    public static function add($data,$table){
        $keys=array_keys($data);
        array_walk($keys, array('PdoMySQL','addSpecilChar'));
        $fieldsStr=join(',',$keys);
        $values="'".join("','",array_values($data))."'";
        $sql="INSERT into {$table} ({$fieldsStr}) VALUES({$values})";
        // echo $sql;
        return self::execute($sql);
    }
    
    /**
    *更新数据
    *@param array data
    *@param string table
    *@param string where
    *@param string order
    *@param string|array limit
     UPDATE user SET username='',password=''...WHERE id<12 ORDER BY username LIMIT 0,1;
    */
    public static function update($data,$table,$where=null,$order=null,$limit=null){
       $sets='';
       foreach ($data as $key => $value) {
       	  $sets.=$key."='".$value."',";
       }
       $sets=rtrim($sets,',');
       $sql="UPDATE {$table} SET {$sets} ".self::parseWhere($where).self::parseOrder($order).self::parseLimit($limit);
       // echo $sql;
       return self::execute($sql);
    }

    /**
    *删除操作表行
    */
    public static function delete($table,$where=null,$order=null,$limit=null){
    	$sql="DELETE FROM {$table} ".self::parseWhere($where).self::parseOrder($order).self::parseLimit($limit);
        return self::execute($sql);
    }

    /**
    *获得最后一次SQL语句
    */
    public static function getLastSql(){
	$link=self::$link;
	if(!$link)return false;
	return self::$queryStr;
    }

    /**
    *获得最后一次插入语句产生的AUTO_INCREMENT
    */
    public static function getLastInsertId(){
	$link=self::$link;
	if(!$link)return false;
	//在每次在execute()中执行SQL语句成功后获得self::$lastInsertId
	return self::$lastInsertId;
    }

    /**
    获取服务器版本
    */
    public static function getDbVerion(){
	$link=self::$link;
	if(!$link)return false;
	//在构造函数中链接数据库成功时获得self::$dbVersion
	return self::$dbVersion;
    }

    /**
    获得数据库中的所有表
   */
   public static function showTables(){
	$tables=array();
	if(self::query("SHOW TABLES")){
		$result=self::getAll();
		foreach($result as $key=>$val){
			$tables[$key]=current($val);
		}
	}
	return $tables;
    }

    /**
    *解析where条件
    *@param unknown $where
    *@return string
    */
    public static function parseWhere($where){
          $whereStr='';
          if(is_string($where)&&!empty($where)){
          	$whereStr=$where;
          }
          return empty($whereStr)?'':' WHERE '.$whereStr;
    }
    /**
    *解析group条件
    *@param unknown $group
    *@return string
    */
     public static function parseGroup($group){
     	$groupStr='';
     	if(is_array($group)){
     		$groupStr=' GROUP BY '.implode(',', $group);
     	}else if(is_string($group)&&!empty($group)){
     		$groupStr.=' GROUP BY '.$group;
     	}
     	return empty($groupStr)?'':$groupStr;
     }
    /**
    *解析having条件
    *@param unknown $having
    *@return string
    */
     public static function parseHaving($having){
     	$havingStr='';
     	if(is_string($having)&&!empty($having)){
     		$havingStr.=' HAVING '.$having;
     	}
     	return $havingStr;
     }
    /**
    *解析order条件
    *@param unknown $order
    *@return string
    */
     public static function parseOrder($order){
     	$orderStr='';
     	if(is_array($order)){
     		$orderStr.=' ORDER BY '.join(',',$order);
     	}else if(is_string($order)&&!empty($order)){
     		$orderStr.=' ORDER BY '.$order;
     	}
     	return $orderStr;
     }
     /**
    *解析限制显示条数limit条件
    *limit 3
    *limit 0,3
    *@param unknown $limit
    *@return 
    */
     public static function parseLimit($limit){
        $limitStr='';
        if(is_array($limit)){
            if(count($limit)>1){
            	$limitStr.=' LIMIT '.$limit[0].','.$limit[1];
            }else{
            	$limitStr.=' LIMIT '.$limit[0];
            }
        }else if(is_string($limit)&&!empty($limit)){
          $limitStr.=' LIMIT '.$limit;
        }
        return $limitStr;
     }
    /**
    *解析字段
    *@param unknown $value
    *@return string
    */
    public static function parseFields($fields){
        if(is_array($fields)){
        	array_walk($fields, array('PdoMySQL','addSpecilChar'));
        	$fieldsStr=implode(',', $fields);
        }else if(is_string($fields)&&!empty($fields)){
              if(strpos($fields, '`')===false){
              	$fields=explode(',', $fields);
              	array_walk($fields, array('PdoMySQL','addSpecilChar'));
              	$fieldsStr=implode(',', $fields);
              }else{
              	$fieldsStr=$fields;
              }
        }else{
        	$fieldsStr='*';
        }
        return $fieldsStr;
    }
    /**
    *通过反引号引用字段
    *@param unknown $value
    *@return string
    */
    public static function addSpecilChar(&$value){
         if($value==='*'||strpos($value,'.')!==false||strpos($value, '`')!==false){
              //不作处理
         }else if(strpos($value, '`')===false){
            $value='`'.trim($value).'`';
         }
         return $value;
    }
    /**
    *执行增/删/改操作，返回受影响的记录条数
    *@param string $sql
    *@param unknown|boolean
    */
    public static function execute($sql=null){
    	$link=self::$link;
    	if(!$link) return false;
    	self::$queryStr=$sql;
    	if(!empty(self::$PDOStatement)) self::free();
    	$result=$link->exec(self::$queryStr);
    	self::haveErrorThrowException();
    	if($result){
    		self::$lastInsertId=$link->lastInsertId();
    		self::$numRows=$result;
    		return self::$numRows;
    	}else{
    		return false;
    	}
    }
    /**
    *释放结果集
    */
    public static function free(){
    	self::$PDOStatement=null;
    }

    public static function query($sql=''){
        $link=self::$link;
        if(!$link)   return false;
        //判断之前是否有结果集，如果有的话，释放结果集
        if(!empty(self::$PDOStatement))
        self::free();
        self::$queryStr=$sql;
        self::$PDOStatement=$link->prepare(self::$queryStr);
        $res=self::$PDOStatement->execute();
        self::haveErrorThrowException();
        return $res;
    }

    public static function querySql($sql='',$option=true){
        $link=self::$link;
        if(!$link)   return false;
        if(!$option){
            $res=$link->query($sql)->fetch(constant("PDO::FETCH_ASSOC"));
        }else{
            $res=$link->query($sql)->fetchAll(constant("PDO::FETCH_ASSOC"));
        }
        return $res;
    }

   public static function haveErrorThrowException(){
       $obj=empty(self::$PDOStatement)?self::$link : self::$PDOStatement;
       $arrError=$obj->errorInfo();
       // print_r($arrError);
       if($arrError[0]!='00000'){
       	self::$error='SQLSTATE: '.$arrError[0].' SQL Error: '.$arrError[2].'<br/> Error SQL:'.self::$queryStr;
       	self::throw_excption(self::$error);
       	return false;
       }
       if(self::$queryStr==''){
       	 self::throw_excption('没有执行SQL语句');
         return false;
       }

   }
    /**
     *自定义错误处理
     *@param unknown $errMsg
     */
	public static function throw_excption($errMsg){
		echo '<div style="width:80%;background-color:#ABCDEF;color:black;font-size:20px;">'.$errMsg.'</div>';
	}
    
    /**
    *销毁连接对象，关闭连接数据库
    */
	public static function close(){
		self::$link=null;
	}
}
// require_once('config.php');
// $PdoMySQL=new PdoMySQL;
// $sql='INSERT into user(username,password,email)';
// $sql.=" VALUES('imooc001','imooc001','imooc001@162.com')";
// var_dump($PdoMySQL->execute($sql));
// echo '<hr/>';
// echo $PdoMySQL::$lastInsertId;
// $sql='DELETE from user where id=23';
// var_dump($PdoMySQL->execute($sql));
// $sql='UPDATE user set username="king123" where id=22';
// var_dump($PdoMySQL->execute($sql));
// $tabName='user';
// $priId='21';
// $fields='*';//'array('username','email')','username,email'
// print_r($PdoMySQL->findById($tabName,$priId,$fields));
// $tables='user';
// print_r($PdoMySQL->find($tables,'id>20','id,username','id desc'));
// print_r($PdoMySQL->find($tables,'id>18','*','id','count(*)>0'));
// print_r($PdoMySQL->find($tables,'id<=18','id,email',null,null,'email DESC,id'));
 // print_r($PdoMySQL->find($tables,null,'*',null,null,null,'5'));
// $data=array(
// 	'username'=>'imooc'
//      'password'=>'123456'
//      'email'=>'imooc@163.com'
//     );
// $PdoMySQL->add($data,$tables);
// $PdoMySQL->update($data,$tables,'id<=25','id DESC','2');
// var_dump($PdoMySQL->delete($tables,'id','id DESC','20,2'));
?>
