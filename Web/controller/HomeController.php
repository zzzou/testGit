<?php

/**
 * 用户公共控制器
 *
 * @name user_controller
 * @package controller
 * @link /controller/user_controller.php
 * @author yunyang
 * @date 2014-3-20
 *
 * @history：
 *
 */
class HomeController extends BaseController {
	function index(){
		if($this->isLogin()){
			$this->redirect(array('c'=>'question'));
		}else{
			$this->redirect(array('c'=>'user', 'a'=>'login'));
		}
	}
}