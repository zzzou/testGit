<?php
/**
 * 字典表
 *
 * @name 字典表
 * @package PJHome
 * @category model
 * @link /model/m_gDictSchema.php
 * @author qiancheng
 * @version 1.0
 */
class DictSchemaModel extends BaseModel{
	//主键
	public $pk = 'id';

	//表名
	public $table = 'gDictSchema';
	
	/**
	 * 查找字典
	 * @param int $bankId 学科id
	 * @param string $cate				
	 * @param int $type
	 * 题型："cate",1；难度："diff",0;年级："grade",2;年度："year",0;类型："examtype",3
	 * @return array 
	 * @author qiancheng
	 *  @data 2014-05-05
	 */
	public function getDictByCate($bankId, $cate, $type=0,$bankType=0){
		
		if ( 0 == $type) {
			$conditions = array(
				'Category'=>$cate
			);
			return $this->findAll($conditions,'SortOrder');
		}
		if (1==$type) {
			$conditions =" Category = '".$cate."' AND Prop2 ='".$bankId."' AND SortOrder !=999 ";
			return $this->findAll($conditions,'SortOrder');
		}
		if(2==$type){
			$conditions = array(
				'Category'=>$cate,
				'Prop2'=>$bankType.""
			);
			return $this->findAll($conditions,'SortOrder');
		}
		if(3==$type){
			$conditions =" Category = '".$cate."' AND Prop2 ='".$bankType."' OR Prop2='0' ";
			return $this->findAll($conditions,'SortOrder');
		}
		
	}
	
	/**
	 * 获取题型名称
	 * @param int $bankId
	 * @param int $categoryId
	 */
	public function getCategoryTitle($bankId,$categoryId){
		$conditions =" Category = 'cate' AND Prop2 = '".$bankId."' AND name =".$categoryId."  AND SortOrder !=999 ";
		$category = $this->find($conditions);
		
		return $category['Title'];
	}

}
