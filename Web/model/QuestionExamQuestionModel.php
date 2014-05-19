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
class QuestionExamQuestionModel extends BaseModel{
	
	//主键
	public $pk="id";
	
	//表名
	public $table = 'question_ExamQuestion';
	
	
	
	/**
	 * 获取具体学科考试题目
	 * @param int $examId
	 * @param int $categoryId
	 */
	public function getCateQuestion($examId,$categoryId){
		$conditions=array(
				'examId'=>$examId,
				'categoryId'=>$categoryId,
		);
		return $this->findAll($conditions,"sortOrder");
	}
	
	
	/**
	 * 修改题目标记分数
	 * @param Integer $questionId 试题ID
	 * @param Integer $score 标记分数
	 */
	public function mark($questionId,$score){
	
		$conditions=array(
				'id'=>$questionId
		);
	
		$row=array(
				'totalScore'=>$score,
				'calibStatus'=>1,
		);
	
		return $this->update($conditions, $row);
	}
	
	/**
	 * 修改排序
	 * @param Integer $questionId 试题ID
	 * @param Integer $sortOrder 排序
	 */
	public function updateOrder($questionId,$sortOrder){
	
		$conditions=array(
				'id'=>$questionId
		);
	
		$row=array(
				'sortOrder'=>$sortOrder
		);
	
		return $this->update($conditions, $row);
	}
	
	
	
	
	/**
	 * 根据试题id查找出当前试题学科下的试题排序信息
	 * @param unknown_type $questionId
	 */
	public function getOrder($questionId){
		$sql="select eq.* from question_ExamQuestion eq,(
				select * from question_ExamQuestion eq1
				where eq1.Id=".$questionId.") eq2
					where eq.CategoryId=eq2.CategoryId and eq.ExamId=eq2.ExamId
						order by eq.SortOrder asc";
		
		return $this->findSql($sql);
	}

	
	
	/**
	 * 获取考试题目
	 * @param int $examId 
	 */
	public function getExamQuestion($examId){
		$conditions=array(
			'examId'=>$examId
		);
		return $this->findAll($conditions,"sortOrder");
	}
	/**
	 * 将考试题目存入数据库
	 * @param array $examId
	 */
	public function addExamQuestion($question){
		$conditions=array(
				'bankId'=>$question['bankId'],
				'examId'=>$question['examId'],
				'categoryId'=>$question['categoryId'],
				'schoolId'=>$question['schoolId'],
				'userId'=>$question['userId'],
				'displayName'=>$question['displayName'],
				'dateCreated'=>$question['dateCreated'],
				'body'=>$question['body'],
				'parse'=>$question['parse'],
				'answer'=>$question['answer']
		);
		return $this->create($conditions);
	}
	
	/**
	 * 根据试题id获取这个题目的全部信息
	 * @param int $questionId
	 */
	public function getQuestion($questionId){
		return $this->find(array('id'=>$questionId));
	}
	
	/**
	 * 根据试题id删除试题
	 * @param int $questionId
	 */
	public function deleteQuestion($questionId){
		return $this->delete(array('id'=>$questionId));
	}
	
	/**
	 * 清空一个题型的所有题目
	 * @param int $examId
	 * @param int $categoryId
	 */
	public function clearQuestionByCategory($examId,$categoryId){
		$conditions=array(
			'examId'=>$examId,
			'categoryId'=>$categoryId
		);
		return $this->delete($conditions);
	}
	
	
}



