<?php
/**
 * 知识点控制器
 *
 * @package controller
 * @link /controller/KnowledgeController.php
 * @author lpsong
 * @date 2014-5-7
 */
class KnowledgeController extends BaseController{
	
	/**
	 * 获得知识点树形结构<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=Knowledge&a=getTreeNode<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getTreeNode(){
		//获取学科，类型，父类id
		$bankId=intval($this->spArgs("bankId"));
		$categoryId=intval($this->spArgs("categoryId"));
		$parentId=intval($this->spArgs("parentId"));
		//查询父类下一层目录结构
		$knowledgeMsg=$this->knowledgeLib->findAllByCategoryIdAndParentId($categoryId,$parentId);
		$result=array();
		
		foreach ($knowledgeMsg as $key=> $value) {
			$result[$key]['id']=$value['Id'];
			$result[$key]['CategoryId']=$value['CategoryId'];
			$result[$key]['ParentId']=$value['ParentId'];
			$result[$key]['name']=$value['Title'];
			$result[$key]['nodepath']=$value['NodePath'];
			
			$msg=$this->knowledgeLib->getKnowHasParents($value['Id']);
			$result[$key]['isParent']=$msg[0]['count']!=0;
			
		}
		return $this->renderJSON($result);
	}
}