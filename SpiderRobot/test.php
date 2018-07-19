<?php
    $requestUrl = "https://blog.csdn.net/myweishanli/article/details/45161641";
    $ip = "220.255.3.170";
    $dk = 80;
 
    $header = array();
    $header[] = ':host:blog.csdn.net';
    $header[] = ':method:GET';
    // $header[] = ':path:/search_product.htm?q=SANA%C9%AF%C4%C8+%B6%B9%C8%E9%C3%C0%BC%A1%BD%FE%CD%B8%C3%C0%C8%DD%D2%BA&type=p&spm=a220m.1000858.a2227oh.d100&from=.list.pc_1_searchbutton';
    $header[] = ':scheme:https';
    $header[] = ':version:HTTP/1.1';
    $header[] = 'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
    $header[] = 'accept-encoding:gzip, deflate, sdch';
    $header[] = 'accept-language:zh-CN,zh;q=0.8';
    $header[] = 'cache-control:max-age=0';
    $header[] = 'cookie:uuid_tt_dd=10_19990772300-1531104221013-175372; __yadk_uid=IVRsBcGlUvxWc4bv793N9wFlvNfDkWz8; dc_session_id=10_1531450022936.353272; Hm_lvt_6bcd52f51e9b3dce32bec4a3997715ac=1531469781,1531470713,1531472219,1531474407; dc_tos=pbt31m; Hm_lpvt_6bcd52f51e9b3dce32bec4a3997715ac=1531487146';
    $header[] = 'referer:https://blog.csdn.net/';
    $header[] = 'upgrade-insecure-requests:1';
    $header[] = 'user-agent:Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36';
    $header[] = 'X-FORWARDED-FOR:'.$ip;
    $header[] = 'CLIENT-IP:'.$ip;
    $Browser  = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $requestUrl);                      //目标地址
    curl_setopt($curl, CURLOPT_REFERER, "http://www.baidu.com/");      //伪造来路地址
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                     //保存到字符串而不是输出
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);                     //5秒内没有响应就断开链接
    // curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);             //代理认证模式
    // curl_setopt($curl, CURLOPT_PROXY, $ip);                            //代理服务器地址
    // curl_setopt($curl, CURLOPT_PROXYPORT, $dk);                        //代理服务器端口
    // curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);             //使用http代理模式
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);                   //HTTP头信息
    curl_setopt($curl, CURLOPT_USERAGENT, $Browser);                   //伪装浏览器
    curl_setopt($curl, CURLOPT_HEADER, 1);                             //不输出header头信息
    curl_setopt($curl, CURLOPT_ENCODING,'gzip');                       //设置解析标示
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);                    //避免302无法跳转
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);                 //https请求 不验证证书
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);                 //https请求 不验证hosts
    $rs = curl_exec($curl);
    curl_close($curl);
    echo '<br/>';
    print_r($rs);
