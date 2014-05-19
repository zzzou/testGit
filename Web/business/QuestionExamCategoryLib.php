<?php
require_once(MODEL_PATH.'/QuestionExamQuestionModel.php');
/**
 * lib考试结构层的操作
 *
 * @package lib
 * @link /lib/QuestionExamCategoryLib.php
 * @author qiancheng
 * @data 2014-05-13
 *
 * @history
 */
 class QuestionExamCategoryLib extends BaseLib{
	protected  $modelName = 'QuestionExamCategoryModel';

	/**
	 * do:添加考试结构
	 * author :by qifang
	 */
	public function addExamCategory($examId,$bankId,$title,$cate){
	  $data=$this->loadCategorysXML();
	  $changeId;
	  $noChangeId;
	  //按XML插入试卷结构
	  foreach ($data['ExamCategory'] as $key=>$value){
	  	if($value['Title']=="title"){
	  		$value['Title']=$title;
	  	}
	  	
	  	$value['ExamId']=$examId;
	  	$value['BankId']=$bankId;
	  	//返回选择题ID
	  	if($value['CateTitle']=="分卷1头部"){
	  		$changeId=$this->add($value);
	  	}
	  	//返回非选择题ID
	  	else if($value['CateTitle']=="分卷2头部"){
	  		$noChangeId=$this->add($value);
	  	}else{
	  		$this->add($value);
	  	}
	  	
	  }
	  //按题型插入试卷结构
	  $sum=1;
	  foreach ($cate as $value) {
	  	if($value['cateId']==2){
	  		$value['ParentId']=$changeId;
	  	}else{
	  		$value['ParentId']=$noChangeId;
	  	}
	  	$value['ExamId']=$examId;
	  	$value['BankId']=$bankId;
	  	$value['Title']=$value['cateName'];
	  	$value['CateId']=1;
	  	$value['TypeId']=2;
	  	$value['IsShow']=1;
	  	$value['Name']=$value['cateId'];
	  	$value['QDesc']="题型注释";
	  	$value['CateTitle']="题型".$sum."头部";
	  	$value['SortOrder']=$sum;
	  	$this->add($value);
	  	$sum++;
	  }
	  
	}
	
	/**
	 * 加载目录xml
	 */
	public function loadCategorysXML(){
	
		if (file_exists(APP_PATH . "/data/categorys.xml")) {
			$xml = simplexml_load_file(APP_PATH . "/data/categorys.xml");
			$data=json_decode(json_encode($xml),true);
			return $data;
		}
	
	}
	
	/**
	 * 删除一个题型及其题目
	 * @param int $examId
	 * @param int $categoryId
	 */
	public function deleteCategoryQuestion($examId,$categoryId){
		//删除题型中的题目
		$questionExamQuestion = new QuestionExamQuestionModel();		
		$questionExamQuestion->clearQuestionByCategory($examId, $categoryId);
		
		//删除题型
		$conditions = array(
			'examId'=>$examId,
			'name'=>$categoryId
		);	
		
		return $this->delete($conditions);	
	}
	
	/**
	 * 试卷设置
	 * @param array $titles
	 * @param array $isShows
	 * @param array $qDescs
	 * @param array $isShowScores
	 * @return boolean
	 */
	public function setCategorys($titles,$isShows,$qDescs,$isShowScores){
	
		try {
			foreach ($titles as $key=>$value){
				$category = array(
						'id'=>substr($key,12),
						'title'=>$value
				);
				$this->updateCategory($category);
			}
			
			foreach ($isShows as $key=>$value){
				$category = array(
						'id'=>substr($key,13),
						'isShow'=>$value
				);
				$this->updateCategory($category);
			}
			
			foreach ($qDescs as $key=>$value){
				$category = array(
						'id'=>substr($key,12),
						'qDesc'=>$value
				);
				$this->updateCategory($category);
			}
			
			foreach (isShowScores as $key=>$value){
				$category = array(
						'id'=>substr($key,18),
						'isShowScore'=>$value
				);
				$this->updateCategory($category);
			}
			
			return true;
			
		} catch (Exception $e) {
			
			return false;
		}
		
	}
	
	
}
