<?php
/**
 * 日志控制器
 *
 * @package controller
 * @link /controller/LogController.php
 * @author fxmei
 * @date 2014-4-10
 *
 * @history：
 *
 */

class LogController extends BaseController{

	/**
	 * 获取所有操作日志<br/>
	 * 开放性:全局开放
	 * 调用方式：/index.php?c=log_controller&a=getAllLogs<br/>
	 * 参数说明：<br/>
	 * 提交方式：get/post</br>
	 */
	public function getAllLogs(){
		header("Content-Type:text/xml");
		$this->log  = new LogLib();
		$result =$this->log->getAlllogs();
		echo $result;
		
	}
}
