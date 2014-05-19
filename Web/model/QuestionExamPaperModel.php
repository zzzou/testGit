<?php
/**
 *考试表question_ExamPaper
 *
 * @name 考试表
 * @package PJHome
 * @category model
 * @link /model/QuestionExamPaperModel.php
 * @author qiancheng
 * @version 1.0
 */
class QuestionExamPaperModel extends  BaseModel{
	//主键
	public $pk = 'id';

	//表名
	public $table = 'question_ExamPaper';
	
	/**
	 * 获取个人组卷记录
	 * @param int $pageIndex 页码
	 * @param int $pageSize 每页显示的数量
	 * @param int $bankId 学科id
	 * @param int $dateFlag 1(今天)，2(昨天)，3(本周,按周一)，4(本月)
	 * @param int $examStatus 0（未考）
	 * @param string $userId
	 * @return array $data 组卷记录及其总数和页数
	 */
	public function getPersonExam($pageIndex,$pageSize,$bankId,$dateFlag,$examStatus,$userId){
	
		$date="";
		switch ($dateFlag){
			case 1:
				$date =date("Y-m-d");
				break;
			case 2:
				$date =date("Y-m-d",strtotime("-1 day"));
				break;
			case 3:
				$date = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
				break;
			case 4:
				$date =date("Y-m-d",mktime(0, 0 , 0,date("m"),1,date("Y")));
				break;
			default:
				break;
		}
	
		$conditions=" status=1 AND bankId = ".$bankId." AND userId = '".$userId."' AND examStatus = ".$examStatus;
	
		if(!empty($date)){
			if ($dateFlag!=2) {
				$conditions.=" AND dataCreted >= '".$date ."'";;
			}
			//昨天
			else{
				$conditions.=" AND dataCreted >= '".$date ."' AND dataCreted < '".date("Y-m-d")."'";
			}
			
		}
		
		$limit= ($pageIndex-1)*$pageSize.",".$pageSize;
		
		//获取个人组卷记录总条数
		$count = $this->findCount($conditions);
		
		//页数
		$pageCount = ceil($count/$pageSize);
		
		$examData=$this->findAll($conditions,"dataCreted DESC",null,$limit);
		
		$data = array(
				'count'=>$count,
				'pageCount'=>$pageCount,
				'data'=>$examData
		);
	
		return $data;
	}
	
	
	/**
	 * 获取学校组卷记录
	 * @param int $pageIndex 页码
	 * @param int $pageSize 每页显示的数量
	 * @param int $bankId 学科id
	 * @param int $dateFlag 1(今天)，2(昨天)，3(本周)，4(本月)
	 * @param string $schoolId
	 * @return array $data 组卷记录及其总数和页数
	 */
	public function getSchoolExam($pageIndex,$pageSize,$bankId,$dateFlag,$schoolId)
	{
		$date="";
		switch ($dateFlag){
			case 1:
				$date =date("Y-m-d");
				break;
			case 2:
				$date =date("Y-m-d",strtotime("-1 day"));
				break;
			case 3:
				$date = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
				break;
			case 4:
				$date =date("Y-m-d",mktime(0, 0 , 0,date("m"),1,date("Y")));
				break;
			default:
				break;
		}
	
		$conditions="status=1 AND isShare =0 AND bankId = ".$bankId." AND schoolId = '".$schoolId."' ";
	
		if(!empty($date)){
		if ($dateFlag!=2) {
				$conditions.=" AND dataCreted >= '".$date ."'";;
			}
			//昨天
			else{
				$conditions.=" AND dataCreted >= '".$date ."' AND dataCreted < '".date("Y-m-d")."'";
			}
		}
	
		$limit= ($pageIndex-1)*$pageSize.",".$pageSize;
		
		//获取个人组卷记录总条数
		$count = $this->findCount($conditions);
		
		//页数
		$pageCount = ceil($count/$pageSize);
		
		$examData=$this->findAll($conditions,"dataCreted DESC",null,$limit);
		
		$data = array(
				'count'=>$count,
				'pageCount'=>$pageCount,
				'data'=>$examData
		);
		
		return $data;	
	}
	
	/**
	 * 将个人试卷共享到学校组卷
	 * @param int  $examId 考试id
	 * @return boolean
	 */
	public function addShareExam($examId){
	
		$conditions=array(
				'id'=>$examId
		);
	
		$row=array(
				'isShare'=>1
		);
		return $this->update($conditions,$row);
	}
	
	/**
	 * 将个人试卷取消共享到学校组卷
	 * @param int  $examId 考试id
	 * @return boolean
	 */
	public function removeShareExam($examId){
	
		$conditions=array(
				'id'=>$examId
		);
	
		$row=array(
				'isShare'=>0
		);
		return $this->update($conditions,$row);
	}
	
	/**
	 * 删除试卷（包括试卷对应的题目和试题目录）
	 * @param int  $examId 考试id
	 * @return boolean
	 */
	public function removeExam($examId){
	
		//删除试卷对应的题目
		$sqlQuestion="DELETE FROM question_examquestion WHERE id =".$examId;
		$this->runSql($sqlQuestion);
	
		//删除试卷对应的目录
		$sqlCategory="DELETE FROM question_examcategory WHERE id =".$examId;
		$this->runSql($sqlCategory);
	
		//删除试题
		$conditions=array(
				'id'=>$examId
		);
		return $this->delete($conditions);
	}
	
	
	/**
	 * 增加一次考试
	 * @param array  $exam 考试
	 * @return 成功：examId, 失败：false
	 */
	public function add($exam){
		
		$row=array(
			'title'=>$exam['title'],
			'datecreted'=>date("Y-m-d H:i:s"),
			'userId'=>$exam['userId'],
			'displayName'=>$exam['displayName'],
			'lastUpdateTime'=>date("Y-m-d H:i:s"),
			'status'=>1,
			'bankId'=>$exam['bankId'],
			"examStatus"=>0,
			//'bankIds'=>$exam['bankIds'],
			'subjectCate'=>$exam['bankCate'],
			'bankType'=>$exam['bankType'],
			'schoolId'=>$exam['schoolId'],
			'gradeId'=>0,
			'paperSize'=>"A4"
		);
			
		return $this->create($row);
	
	}

}