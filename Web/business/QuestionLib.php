<?php
/**
 * 表question_x_xxxx的操作(对应不同的学科不同的表)
 * 现在只用一个表Question_H_Chinese
 *
* @package lib
* @link /lib/QuestionLib.php
* @author qiancheng
* @data 2014-05-05
*
* @history
*/

class QuestionLib extends BaseLib{
	protected $modelName = 'QuestionModel';
	

	/**
	 * 根据条件包括分页从数据库中查询题目 
	 * @param array $conditions
	 * $conditions = array(
	 *	'pageIndex'=>1,(页码，必须)
	 *	'pageSize'=>10,（每页的显示的数量，必须）
	 *  'bankId'=>10,（科目id，必须）
	 *  'knowledgeId'=>8,（知识点，可选）
	 *  'diffId'=>3,（难度，可选）
	 *  'year'=>2012,（年度，可选）
	 *  'typeId'=>5,（类型，可选）
	 *  'gradeId'=>3,（年级，可选）
	 *  'teacherId'=>"7oogamcif4rb5xjyuleafg"（教师，可选）
	 *	)
	 * @return 数组
	 */
	public function getQuestion($conditions){

		if($conditions['pageSize']<=0 ||$conditions['pageIndex']<=0 || $conditions['bankId'<=0]){
			return ;
		}
		
		//获取符合条件的题目总数
		$queryConditions = $this->QueryConditons($conditions);
		
		$count = $this->getQuestionCount($queryConditions);
				
		$pageCount = $count / $conditions['pageSize'];
		$pageCount=intval($pageCount);
		
		if ( $count % $conditions['pageSize']>0 ) {
			$pageCount=$pageCount+1;
		}
		
		//查询到的题目的数据
		$questionData=$this->getQuestionData($conditions['pageIndex'], $conditions['pageSize'], $queryConditions);
		$data = array(
			'count'=>$count,
			'pageCount'=>$pageCount,
			'data'=>$questionData
		);
		
		return $data;
				
	}

}