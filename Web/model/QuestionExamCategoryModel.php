<?php
/**
 *考试目录表question_ExamCategory
 *
 * @name 考试表
 * @package PJHome
 * @category model
 * @link /model/QuestionExamCategoryModel.php
 * @author qiancheng
 * @version 1.0
 */
class QuestionExamCategoryModel extends BaseModel{
	
	//主键
	public $pk="id";
	
	//表名
	public $table = 'question_ExamCategory';
	
	
	
	/**
	 * 修改题型标记总分
	 * @param String $categoryId 题型在试卷上的ID
	 * @param String $qDesc 需要修改的总分标注
	 */
	public function mark($categoryId,$qDesc){
	
		$conditions=array(
				'id'=>$categoryId
		);
	
		$row=array(
				'qDesc'=>$qDesc,
		);
	
		return $this->update($conditions, $row);
	}
	
	
	/**
	 * 增加考试结构
	 * @param array $categorys
	 */
	public function add($categorys){
		
		$condition=array(
		    "id"=>uniqid(),
		    "cateTitle"=>$categorys['CateTitle'],
			"title"=>$categorys['Title'],
			"qDesc"=>$categorys['QDesc'],
			"cateId"=>$categorys['CateId'],
			"typeId"=>$categorys['TypeId'],
			"isShow"=>$categorys['IsShow'],
			"sortOrder"=>$categorys['SortOrder'],
			"examId"=>$categorys['ExamId'],
			"name"=>$categorys['Name'],
			"parentId"=>$categorys['ParentId'],
			"bankId"=>$categorys['BankId'],
			"isShowScore"=>$categorys['IsShowScore'],
			"quesCount"=>$categorys['QuesCount'],
			"totalScore"=>$categorys['TotalScore']
		);
		
		 $this->create($condition);
		 return $condition['id'];
	}
	
	/**
	 * 获取考试结构
	 * @param int $examId
	 */
	public function getExamCategory($examId){
		$conditions=array(
			'examId'=>$examId
		);
		
		return $this->findAll($conditions,"sortOrder");
	}
	
	/**
	 * 获取卷头
	 * @param int $examId
	 */
	public function getExamCateTitle($examId){
		$conditions=array(
			'examId'=>$examId,
			'typeId'=>1
		);
		
		return $this->findAll($conditions,"sortOrder");
	}
	
	/**
	 * 获取卷体
	 * @param int $examId
	 */
	public function getExamBody($examId){
		$conditions=array(
			'examId'=>$examId,
			'typeId'=>2,
			'parentId'=>''
		);

		return $this->findAll($conditions,"sortOrder");
	}
	
	/**
	 * 获取卷体中的题型
	 * @param int $examId
	 */
	public function getExamBodyCategory($examId){
	
		$conditions = "examId =".$examId." 	AND typeId = 2 AND parentId is not null";
		return $this->findAll($conditions,"sortOrder");
	}
	
	/**
	 * 修改试卷结构
	 * @param array $category
	 */
	public function updateCategory($category){
		$conditions=array(
			'id'=>$category['id']
		);
		
		$row = array();
		
		if (!empty($category['title'])) {
			$row['title']=$category['title'];
		}
		if (!empty($category['isShow'])) {
			$row['isShow'] = $category['isShow'];
		}
		if (!empty($category['isShowScore'])) {
			$row['isShowScore']=$category['isShowScore'];
		}
		if (!empty($category['qDesc'])) {
			$row['qDesc']=$category['qDesc'];
		}
		return $this->update($conditions, $row);
	}
	
	
}


