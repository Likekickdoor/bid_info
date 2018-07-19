<?php
/**
 *@param OpenURL类，输入等待爬取URL数组，变可以把结果以‘数组’形式返回，
其内容可能是json形式的，也可以是其他的，如同file_get_contents()
 */
class OpenUrlClass
{
	private $urls=[];
	private $url='';
	private $proxy=false;
	private $userAgent=[
             'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)',
             'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',
             'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
             'Opera/8.0 (Windows NT 5.1; U; en)',
             'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.50',
             'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
             'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0'
        ];
  private $proxy_ip=[
    		 '61.135.164.220:80',
    	  ];
  private $X_FORWARDED_FOR=[
    		 '61.135.169.125',
    		 '220.170.182.32',
    		 '202.108.22.5',
    	  ];
  private $postArr=[];//POST数据
  private $cookiehead;//cookie头
  public  $headCode=-1;//默认不存在
  
	public function _Seturls($urls) {
		$this->urls=$urls;
	}
	public function _Seturl($url) {
		$this->url=$url;
	}
	public function _Setproxy($ip=null) {
		$this->proxy=true;
		if($ip!=null){
			$this->proxy_ip=$ip;
		}
	}
  public function _Setpost($postArr){
    $this->postArr=$postArr;
  }

  public function _Setcookie($cookiehead){
    $this->cookiehead=$cookiehead;
  }
  /**
  *并行Open
  */
	public function curl_array_file() {
		$handles=curl_multi_init();//创建批处理cURL句柄
		foreach ($this->urls as $i => $url) {
			$handle[$i] = curl_init();
			curl_setopt($handle[$i], CURLOPT_URL, $url);
    		curl_setopt($handle[$i], CURLOPT_USERAGENT, $this->userAgent[mt_rand(0,5)]);//用户代理
    		curl_setopt($handle[$i], CURLOPT_HEADER ,0);//启用1时会将头文件的信息作为输出
	  		curl_setopt($handle[$i], CURLOPT_TIMEOUT, 60);//传递数据等待时间
    		curl_setopt($handle[$i], CURLOPT_CONNECTTIMEOUT,60); //在尝试连接时等待的秒数。设置为0，则无限等待
    		curl_setopt($handle[$i],CURLOPT_RETURNTRANSFER,true); // 不将爬取代码写到浏览器，而是转化为字符串//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
    		curl_setopt($handle[$i], CURLOPT_FOLLOWLOCATION, 1);//若给定url自动跳转到新的url,有了参数可自动获取新url内容：302跳转
    		$X_F_F=$this->X_FORWARDED_FOR[mt_rand(0,2)];$C_IP=$X_F_F;
    		curl_setopt($handle[$i], CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$X_F_F, 'CLIENT-IP:'.$C_IP));  //构造IP
    		curl_setopt($handle[$i], CURLOPT_REFERER, "http://www.baidu.com/");//构造来路
    		// curl_setopt($conn[$i], CURLOPT_CUSTOMREQUEST, 'GET');
    		curl_setopt($handle[$i], CURLOPT_ENCODING, 'gzip, deflate');
    		curl_setopt($handle[$i], CURLOPT_SSL_VERIFYPEER, false); //FALSE 禁止 cURL 验证对等证书
    		curl_setopt($handle[$i], CURLOPT_SSL_VERIFYHOST, false); //FALSE 不检查证书
    		if($this->proxy==true){
    		curl_setopt($handle[$i], CURLOPT_HTTPPROXYTUNNEL, 1);
    		curl_setopt($handle[$i], CURLOPT_PROXY,$this->proxy_ip[0]);	
    		}
    		curl_multi_add_handle ($handles,$handle[$i]);//向curl批处理会话中添加单独的curl资源
		}
		$active=null;
		do{
			curl_multi_exec($handles, $active);
		}while ($active>0);
		$page_arr=[];//装取打开的URL容器
		for($i=0;$i<count($this->urls);$i++) {
			$page_temp=curl_multi_getcontent($handle[$i]);
			array_push($page_arr, $page_temp);
			// $headCode=curl_getinfo($handle[$i],CURLINFO_HTTP_CODE);输出每个的状态码
		}
		foreach ($this->urls as $i => $url) {
			curl_multi_remove_handle($handles,$handle[$i]);//移除curl批处理句柄资源中的某个句柄资源
    		curl_close($handle[$i]);
		}
		curl_multi_close($handles);//关闭一组cURL句柄
		return $page_arr;
	}
  /**
  *单一Open
  */
	public function curl_one_file() {
		  $ch = curl_init();
   		curl_setopt($ch, CURLOPT_URL, $this->url);
   		curl_setopt($ch, CURLOPT_HEADER, 0);//启用1时会将头文件的信息作为输出
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不将爬取代码写到浏览器，而是转化为字符串//TRUE 
  		//若给定url自动跳转到新的url,有了下面参数可自动获取新url内容：302跳转
   		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   		//设置cURL允许执行的最长秒数。
   		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
   		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);//在尝试连接时等待的秒数。设置为0，则无限等待
   		curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent[mt_rand(0,5)]);
   		$X_F_F=$this->X_FORWARDED_FOR[mt_rand(0,2)];$C_IP=$X_F_F;
   		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$X_F_F, 'CLIENT-IP:'.$C_IP)); //构造IP
   		curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com/");//构造来路
   		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
   		//curl_setopt($ch, CURLOPT_VERBOSE,true);//报告意外的事
   		if($this->proxy==true){
    		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    		curl_setopt($ch, CURLOPT_PROXY,$this->proxy_ip[0]);	
    		// curl_setopt($ch, CURLOPT_PROXY, 'proxy.baibianip.com'); //代理服务器地址
    		// curl_setopt($ch, CURLOPT_PROXYPORT, '8000'); //代理服务器端口
    	}
      if(!empty($this->postArr)){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->postArr));
      }
      if(!empty($this->cookiehead)){
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookiehead);
      }
   		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//FALSE 禁止 cURL 验证对等证书
   		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//FALSE 不检查证书 
   		$page=curl_exec($ch);
   		$this->headCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);//输出每个的状态码		
		return $page;
	}
  /**
  *获取Cookie并存储
  */
  public function login_get_cookie($url,$cookie,$postArr) {  
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); //存放cookie的文件
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArr)); 
    curl_exec($ch);  
    curl_close($ch); 
  }

}



// $one = new OpenUrlClass();
// $one->_Seturls(['http://www.chinabidding.com/search/proj.htm']);
// $one->_Setproxy(null);
// $res = $one->curl_array_file();
// print_r($res[0]);
// $one->_Seturl('http://www.chinabidding.com/search/proj.htm');
// $one->_Setproxy();
// $res = $one->curl_one_file();
// var_dump($res);
// $postArr=['fullText'=>'',
// 'pubDate'=>'3',
// 'infoClassCodes'=>'0105',
// 'normIndustry'=>'08',
// 'zoneCode'=>'',
// 'fundSourceCodes'=>'',
// 'poClass'=>'',
// 'rangeType'=>'',
// 'currentPage'=>'2'
// ];
// $one->_Setcookie('JSESSIONID=657D40BC3807127A4B1CA97C19C6FAF4;');
// $one->_Setpost($postArr);
// $res=$one->curl_one_file();
// print_r($res);
?>