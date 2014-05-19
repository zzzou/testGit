<?php
/**
 * 阅卷系统控制器
 *
 * @package controller
 * @link /controller/MakingSystemController.php
 * @author lpsong
 * @date 2014-5-12
 *
 * @history：
 *
 */
class MarkingSystemController extends BaseController{
	public function index(){
        $this->modelID = 23;
		$this->render();
	}
}