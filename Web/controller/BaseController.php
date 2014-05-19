<?php
/**
 * 基本控制器类
 *
 * @package controller
 * @link /controller/BaseController.php
 * @author lpsong
 */
class BaseController extends spController {
	protected $layout = 'default';
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct ();
		
    	$this->piwikUrl = PIWIK_URL;
    	$this->piwikWebID = PIWIK_WEB_ID;
    	$this->piwikDomain = PIWIK_DOMAIN;
	}
	
	/**
	 * 魔术函数，返回已赋值的变量值
	 */
	public function __get($name){
		if(preg_match('/\w+Lib/', $name)){
			$lib = spClass(ucfirst($name));

			$this->{$name} = $lib;
		}

		return parent::__get($name);
	}

	/**
	 * 生成返回值
	 * 
	 * @param int $state 默认值为0，表示正常状态，其他数字表示异常状态
	 * @param string|NULL $message 返回信息
	 * @param array|string|stdObj|NULL $data 返回数据
	 * @return string 将
	 */
	public function buildResult($state=0, $message='', $data=array()) {
		$arr = array (
			"state" => $state,
			"message" => $message,
			"data" => $data 
		);
		return json_encode($arr);
	}

	/**
	  * 返回 404
	  *
	  * @param string $msg 键
	  */
	protected function notFound($msg='404 Not Found'){
		header('HTTP/1.1 404 Not Found');
		header("status: 404 Not Found");

		exit($msg);
	}
	
	/**
	  * 获取当前session中的user_id
	  */
	protected function getSessionUserId(){
		return $_SESSION['userInfo']['user_id'];
	}

	/**
	 * 返回用户当前是否登陆
	 */
	protected function isLogin(){
		return isset($_SESSION['userInfo']);
	}

	/**
	  * 渲染返回数据为JSON
	  * 
	  * @param array|string $data json数据 
	  *
	  */
	protected function renderJSON($data){
		header("content-type:application/json; charset=utf-8");
		echo is_array($data) ? json_encode($data) : $data;
		exit;
	}

	/**
	  * 渲染视图
	  *
	  * @param string $tpl 模版
	  * @param string $layout 视图
	  * 
	  */
	protected function render($tpl=null, $layout=true){
		global $__controller,$__action,$__module;
		$controller = str_replace('Controller', '', $__controller);
		if(!$tpl){
			$tpl = $controller . '/' . $__action;
		}
		if($__module){
			$tpl = $__module . '/' . $tpl;
		}
		$tpl .= '.html';
		if($layout===true){
			$layout = $this->layout;
		}
		if ($layout){
			$layout = 'layouts/' . $layout . '.html';
		}
		$html = $this->display($tpl, false);
		if($layout){
			$this->pageContent = $html;
			$html = $this->display($layout, false);
		}
		echo $html;
		exit;
	}

	/**
	  * 下载文件，解决中文文件名问题
	  *
	  * @param string $fileContent 文件内容
	  * @param string $filename 下载文件文件名
	  *
	  */
	protected function downloadFile($fileContent, $filename){
		header ( "Content-Type: application/force-download" );
					
		//不同浏览器处理
		$encodedFilename = rawurlencode($filename).'.doc';
		$userAgent = $_SERVER["HTTP_USER_AGENT"];
		if(preg_match("/MSIE/i", $userAgent) || preg_match("/Trident\/7.0/", $userAgent)) {
			// support ie
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		} else if (preg_match("/Firefox/", $userAgent)) {
			// support firefox
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $encodedFilename . '"');
		} else {
			// support safari and chrome
			header ( "Content-Disposition: attachment; filename=\"$filename\"" );
		}
			
		header ( "Content-length: " . ( string ) (strlen ( $fileContent )) );
		header ( "Expires: " . gmdate ( "D, d M Y H:i:s", mktime ( date ( "H" ) + 2, date ( "i" ), date ( "s" ), date ( "m" ), date ( "d" ), date ( "Y" ) ) ) . " GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Pragma: no-cache" );
		echo $fileContent;
	}

	/**
	  * 跳转到url
	  *
	  * @param string|array $route
	  */
	protected function redirect($route){
		global $__controller;
		if(is_array($route)){
			if(isset($route['c'])){
				$controller = $route['c'];
			}else{
				$controller = $__controller;
				$controller = str_replace('Controller', '', $controller);
				$controller = lcfirst($controller);
			}
			$action = isset($route['a']) ? $route['a'] : 'index';
			if($action=='index'){
				$url = "index.php?c=$controller";
			}else{
				$url = "index.php?c=$controller&a=$action";
			}

			if(isset($route['c'])){
				unset($route['c']);
			}
			if(isset($route['a'])){
				unset($route['a']);
			}
			$params = '';
			foreach ($route as $key => $value) {
				$params .= '&' . $key . '=' . $value;
			}
			if($params){
				$url .= $params;
			}
		}

	    header("Location: $url");
	    exit;
	}
} 