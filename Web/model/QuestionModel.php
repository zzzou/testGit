<?php
/**
 * 语文知识点表
 *
 * @name 语文知识点表 Question_H_Chinese
 * @package PJHome
 * @category model
 * @link /model/Question_H_Chinese.php
 * @author qiancheng
 * @version 1.0
 */
class QuestionModel extends  BaseModel{
	//主键
	public $pk = 'id';

	//表名
	public $table = 'Question_H_Chinese';
	
	//对应的知识点表
	public $knowledgeTable="QuestionKnowledge_H_Chinese";
	
	/**
	 * 从数据库中取题目
	 * @param int $pageIndex 页码
	 * @param int $pageSize 页的大小
	 * @param string $queryConditions 条件
	 * @return 题目数组
	 */
	public function getQuestionData($pageIndex,$pageSize,$queryConditions){
	
		$sql = " SELECT  ques.* FROM ".$this->table . $queryConditions." LIMIT ".($pageIndex-1)*$pageSize.",".$pageSize;
		
		return $this->findSql($sql);
	
	}
	
	/**
	 *取符合条件的题目数量
	 * @param string $queryConditions 条件组合
	 * @return 题目数量
	 */
	public function getQuestionCount($queryConditions){
	
		$sql="SELECT COUNT(*) AS SP_COUNTER FROM ".$this->table.$queryConditions;
	
	
		$result=$this->findSql($sql);
	
		return $result[0]['SP_COUNTER'];
	
	}
	
	/**
	 * 组合查询题目的条件
	 * @param array $conditions 获取题目的条件
	 * @return 条件
	 */
	public function QueryConditons($conditions)
	{
	
		$queryConditon="";
		//判断是我的题库，还是参考题库
		if (!empty($conditions['teacherId'])) {
			$queryConditon=" ques WHERE sourceType>=2 AND isDraft<>1 ";
			$queryConditon.=" AND teacherId = '".$conditions['teacherId']."'";
		}
		else {
			$queryConditon=" ques WHERE sourceType=1 AND ques.id in (SELECT knowledge.sourceQuestionId  FROM QuestionKnowledge_H_Chinese knowledge  ";
		}
	
	
		if (!empty($conditions['knowledgeId'])) {
			$queryConditon.=" where knowledge.knowledgeId =" . $conditions['knowledgeId'] ." AND knowledge.cate=2 ) ";
		}
		else if(empty($conditions['teacherId'])){
			$queryConditon.=") ";
		}
	
		if (!empty($conditions['year'])) {
			$queryConditon.=" AND yearNo =".$conditions['year'] ;
		}
	
		if (!empty($conditions['diffId'])) {
			$queryConditon.=" AND diffId = ".$conditions['diffId'] ;
		}
	
		if (!empty($conditions['categoryId'])) {
			$queryConditon.=" AND categoryId = ".$conditions['categoryId'] ;
		}
	
		if (!empty($conditions['typeId'])) {
			$queryConditon.=" AND typeId = ".$conditions['typeId'] ;
		}
	
		if (!empty($conditions['gradeId'])) {
			$queryConditon.=" AND gradeId = ".$conditions['gradeId'] ;
		}
		
		if (!empty($conditions['bankId'])) {
			$queryConditon.=" AND bankId = ".$conditions['bankId'] ;
		}

        if (isset($conditions['IsSigned'])){
            $queryConditon.=" AND IsSigned = ".$conditions['IsSigned'];
        }
	
		return $queryConditon;
	}
	
	/**
	 * 新建试题
	 * @param array question 题目
	 * @return  boolean
	 */
	public function addNewQuestion($question){
	
		$data = array(
			'id'=>uniqid(),
			'schoolId'=>$question['schoolId'],
			'teacherId'=>$question['userId'],
			'isQuote'=>1,
			'sourceType'=>3,
			'dateCreated'=>date("Y-m-d H:i:s"),
			'lastUpdateTime'=>date("Y-m-d H:i:s"),
			'lastUpdateTeacherId'=>$question['userId'],
			'isDraft'=>-1,
			'isSigned'=>"0",
			'body'=>$question['body'],
			'answer'=>$question['answer'],
			'parse'=>$question['parse']
		);
		
		return $this->create($data);
	}
	
	/**
	 * 修改试题
	 * @param array question 题目
	 * @return  boolean
	 */
	public function editQuestion($question){
	
		$conditions=array(
			'id'=>$question['id']
		);
	
		$row=array(
			'lastUpdateTime'=>date("Y-m-d H:i:s"),
			'lastUpdateTeacherId'=>$question['userId'],
			'body'=>$question['body'],
			'answer'=>$question['answer'],
			'parse'=>$question['parse']
		);
	
		return $this->update($conditions,$row);
	}
	
	/**
	 * 标定
	 * @param array ids  多个题目的组成的数组
	 * @param array question 题目属性
	 * @return  boolean
	 */
	public function sign($ids,$question){
	
		$conditions=" 0=1 ";
		
		foreach ($ids as $key=>$value) {
			$conditions.=" OR id = '".$value."' ";
		}
		
		$row=array(
				'lastUpdateTime'=>date("Y-m-d H:i:s"),
				'lastUpdateTeacherId'=>$question['userId'],
				'knowledgeId'=>$question['knowledgeId'],
				'isSigned'=>1
		);
		
		if (!empty($question['diffId'])) {
			$row['diffId']=$question['diffId'];
		}
		if (!empty($question['categoryId'])) {
			$row['categoryId']=$question['categoryId'];
		}
		if (!empty($question['gradeId'])) {
			$row['gradeId']=$question['gradeId'];
		}
		if (!empty($question['teacherSource'])) {
			$row['teacherSource']=$question['teacherSource'];
		}
		if (!empty($question['num'])) {
			$row['num']=$question['num'];
		}
		if (!empty($question['ability'])) {
			$row['ability']=$question['ability'];
		}
		return $this->update($conditions,$row);
	}
		
	
	/**
	 * 判断题目是否被引用到我的题库
	 * @param int $sourceId
	 * @param string $userId
	 * @return boolean
	 */
	public function isQuote($sourceId,$userId)
	{
		$conditions =" sourceId=".$sourceId." AND teacherId= '".$userId."' AND  sourceType>1";
	
		$count=$this->findCount($conditions);
	
		if (count>0) {
			return true;
		}
	
		return false;
	}
	
	/**
	 * 从参考题库引用到我的题库
	 * @param string $id 题目id
	 * @param string $userId 用户id
	 * @param string $schoolId 学校id
	 * @return 成功是id，失败是false。
	 */
	public function addMyQuestion($id,$userId,$schoolId){
		$conditions=array(
			'id'=>$id
		);
	
		$question=$this->question->find($conditions);
			
		$question['id']=uniqid();
		$question['schooId']=$schoolId;
		$question['teacherId']=$userId;
		$question['isQuote']=1;
		$question['sourceType']=3;
		$question['lastUpdateTime']=date("Y-m-d H:i:s");
		$question['lastUpdateTeacherId']=$userId;
		$question['teacherSource']="引用于学校题库";
		$question['isDraft']=-1;
		$question['isSigned']="0";
	
		return $this->create($question);
	}

}