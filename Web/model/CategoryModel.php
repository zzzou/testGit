<?php
/**
 * 学科类型表
 *
 * @name 学科类型表
 * @package PJHome
 * @category model
 * @link /model/m_category.php
 * @author lbdai
 * @version 1.0
 */
class CategoryModel extends BaseModel{
	//主键
	public $pk='Id';
	//表名
	public $table='category';
	
	public function findAllByBankId($bankId){
		return $this->findAll(array('bankId'=>$bankId));
	}
}
