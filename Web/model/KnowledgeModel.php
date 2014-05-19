<?php
/**
 * 学科知识点表
 *
 * @name 学科知识点表
 * @package fileManager
 * @category model
 * @link /model/KnowledgeModel.php
 * @author lbdai
 * @version 1.0
 */
class KnowledgeModel extends BaseModel{
	//主键
	public $pk='Id';
	//表名
	public $table='knowledge';

	/**
	 * 根据知识点sourceid判断是否有子树
	 * @param unknown_type $sourceId
	 */
	public function getKnowHasParents($sourceId){
		$sql="SELECT count(*) as count from knowledge k1 where  k1.ParentId=$sourceId";
		return $this->findSql($sql);
	}
	
	public function findAllByCategoryIdAndParentId($categoryId, $parentId){
		return $this->findAll(array('categoryId'=>$categoryId, 'parentId'=>$parentId));
	}
}