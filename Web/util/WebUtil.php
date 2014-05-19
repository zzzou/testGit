<?php
function web_post($url, $params=array(), $cookieFile=false){
    $curl = curl_init();

    $header[] = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 10";
    $header[] = "Accept-Charset: GB2312,ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: zh-cn,zh,en-us,en;q=0.5";

    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    if(file_exists($cookieFile) && file_get_contents($cookieFile)){
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
    }else{
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
    }

    $result = curl_exec($curl);

    $i=0;
    while((!$result)&&$i<0){
        sleep($i);
        $result = curl_exec($curl);
        $i++;
    }

    curl_close($curl);

    return $result;
}

function web_ti_api($url, $params=array()){
    $cookieFile = tempnam("tmp", uniqid());

    web_post(TOPIC_URL_LAN . 'home/index', array(
        'username'=>'wl',
        'pwd'=>'111111'
    ), $cookieFile);

    return web_post(TOPIC_URL_LAN . $url, $params, $cookieFile);
}

function getBaseUrl()
{
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF'];
     
    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
    $pathInfo = pathinfo($currentPath);
     
    // output: localhost
    $hostName = $_SERVER['HTTP_HOST'];
     
    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
     
    // return: http://localhost/myproject/
    $dirname = $pathInfo['dirname'];
    if($dirname=='/' || $dirname=='\\'){
        $dirname = '';
    }
    return $protocol.$hostName.$dirname."/";
}

function makeUrl($page=1){
    $params = $_GET;
    if($page!=1){
        $params['page'] = $page;
    }else{
        unset($params['page']);
    }
    $url = getBaseUrl() . '/index.php?' . http_build_query($params);
    return $url;
}