<?php

/**
 * 试题栏表
 *
 * @name 试题栏表
 * @package fileManager
 * @category model
 * @link /model/CartQuestionModel.php
 * @author lbdai
 * @version 1.0
 */
class CartQuestionModel extends BaseModel{
	//主键
	public $pk='Id';
	//表名
	public $table='cart_question';
	
	/**
	 * 增加试题栏记录
	 * @param array $QuesArray
	 */
	public function addQuestion($QuesArray){
		return $this->create($QuesArray);
	}
	/**
	 * 删除试题栏记录
	 * @param array $QuesArray
	 */
	public function delQuestion($QuesArray){
		$this->delete($QuesArray);
	}
	/**
	 * 查找试题篮记录
	 * @param int  $id
	 */
	public function getCartQuestionById($userId){
		return $this->findAll(array("userId"=>$userId));
	}
	
	/**
	 * 根据用户Id和问题id查询
	 * @param int $userId
	 * @param int $id
	 */
	public function findAllByUserIdAndQuestionId($userId,$id){
		return $this->findAll(array("userId"=>$userId,"questionId"=>$id));
	}
	
}