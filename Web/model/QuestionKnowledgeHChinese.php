<?php
/**
 * 语文知识点表
 *
 * @name 语文知识点表 QuestionKnowledge_H_Chinese
 * @package PJHome
 * @category model
 * @link /model/QuestionKnowledge_H_Chinese.php
 * @author qiancheng
 * @version 1.0
 */
class QuestionKnowledgeHChinese extends  BaseModel{
	//主键
	public $pk = 'id';

	//表名
	public $table = 'QuestionKnowledge_H_Chinese';
	
	/**
	 * 根据问题id和学科查询
	 * @param int $QuestionId
	 * @param int $Cate
	 */
	public function findAllBySourceQuestionIdAndCate($QuestionId,$Cate){
		
		return $this->findAll(array("SourceQuestionId"=>$QuestionId,"Cate"=>$Cate));
	}

}