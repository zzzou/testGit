<?php
/**
 * 学科类型控制器
 *
 * @name CategoryController
 * @package controller
 * @link /controller/CategoryController.php
 * @author lbdai
 * @date 2014-5-7
 *
 */
class CategoryController extends BaseController{
	
	/**
	 * 获得CategoryTree<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=Category&a=getCategoryTree<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getCategoryTree(){
		//默认为高中语文
		$BankId=10;
        $cateoryMsg = $this->categoryLib->findAllByBankId($BankId);
		return $this->renderJSON($cateoryMsg);
	}

}