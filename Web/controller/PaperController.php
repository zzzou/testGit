<?php
/**
 * 试卷控制器
 *
 * @package controller
 * @link /controller/PaperController.php
 * @author wqyan
 */
require_once(UTIL_PATH . '/TopicUtil.php');

class PaperController extends BaseController{
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct ();
		$this->modelID = 18;
	}
	
	/**
	 * 参考题库<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=paper&a=getPaper</br>
	 * @author wqyan
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getPaper() {
		$id=$this->spArgs('id');   //id是examId
        $question =$this->QuestionExamQuestionLib->getExamQuestion($id);
        $paperHeader =$this->QuestionExamCategoryLib->getExamCateTitle($id);
        $paperContent =$this->QuestionExamCategoryLib->getExamBodyCategory($id);

        /*获取第一级目录*/
        $oneLevel =array();
        foreach($paperContent as $key=>$value)
        {
        	if($value['ParentId']==''||$value['ParentId']==null)
        	{
        		$oneLevel[] = $value;
        		unset($paperContent[$key]);  //把第一级去掉，剩下第二级
        	}       	
        }
        //dump($paperContent);die();
        /*对第二级汉字编号*/
        $num =1;
        foreach ($paperContent as $key=>$value)
        {      	
        	switch ($num){
        		case 1 :$paperContent[$key]['hanzi'] ="一"; break;
        		case 2 :$paperContent[$key]['hanzi'] ="二"; break;
        		case 3 :$paperContent[$key]['hanzi'] ="三"; break;
        		case 4 :$paperContent[$key]['hanzi'] ="四"; break;
        		case 5 :$paperContent[$key]['hanzi'] ="五"; break;
        		case 6 :$paperContent[$key]['hanzi'] ="六"; break;
        		case 7 :$paperContent[$key]['hanzi'] ="七"; break;
        		case 8 :$paperContent[$key]['hanzi'] ="八"; break;
        		default:$paperContent[$key]['hanzi'] ="九"; 
        	}
        	$num++;
        }
       // dump($paperContent);die();
        foreach ($oneLevel as $key=>$value)
        {
        	$tempArray =array();
        	foreach ($paperContent as $key2=>$value2)
        	{
        		if($value['Id'] == $value2['ParentId'])
        	    {
        			$tempArray[] = $value2;
        	    }
        	}
        	$oneLevel[$key]['two'] =$tempArray;   //拿到卷体。等待题型中的题目。。
        }
        
        //把题目加入题型数组中。
        foreach ($oneLevel as $key=>$value )
        {
        	foreach ($oneLevel[$key]['two'] as $key2=>$value2)
        	{
        		$tempArray =array();
        		foreach ($question as $key3=>$value3)
        		{
        			if($value2['Name']==$value3['CategoryId'])
	        		{
	       			    $tempArray[] = $value3;
	        		}
        		}
        		$oneLevel[$key]['two'][$key2]['list'] =$tempArray;      	
        	}
        }      
        
        $this->paperHeader =$paperHeader;
        $this->paperContent = $oneLevel;
       // dump($this->paperContent);die();
        unset($oneLevel[0]);
        $this->twoSection = $oneLevel;
        //dump($this->twoSection);die();
        $this->examId =$id;
		$this->render();
	}
	
	//测试获取考试题目
	public function testExamQuestion(){
		$data =$this->QuestionExamQuestionLib->getExamQuestion(3868);
		var_dump($data);
	}
	//测试获取考试目录
	public function testExamCategory(){
		$data =$this->QuestionExamCategoryLib->getExamCategory(3903);
		var_dump($data);
	}
	//测试获取题目种类
	public function testgetCate(){
		$this->problemTypes=$this->DictSchemaLib->getDictByCate(10,"cate",1);
		var_dump($this->problemTypes);die();
	}
	
	//字典
	public function testgDictSchema()
	{
		$lib_gDictSchema = $this->getLibLayerObject("DictSchemaLib");
		$data=$lib_gDictSchema->getDictByCate(10,"cate",1);
		dump($data);
	}
	
	//获取卷头
	public function testgetExamCateTitle(){
		$data =$this->QuestionExamCategoryLib->getExamCateTitle(3868);
		var_dump($data);
	}
	
	//获取卷体目录
	public function testgetExamBodyCategory(){
		$data =$this->QuestionExamCategoryLib->getExamBodyCategory(3868);
		var_dump($data);
	}
	
	/**
	 * 获得试卷设置对话框数据
	 * 开放性：全局开放<br/>
	 */
	public function getDialog(){	
		$examId=$this->spArgs('examId');
		
		$question =$this->QuestionExamQuestionLib->getExamQuestion($examId);
		$paperHeader =$this->QuestionExamCategoryLib->getExamCateTitle($examId);
		$paperContent =$this->QuestionExamCategoryLib->getExamBodyCategory($examId);
		/*获取第一级目录*/
		$oneLevel =array();
		foreach($paperContent as $key=>$value)
		{
			if($value['ParentId']==''||$value['ParentId']==null)
			{
				$oneLevel[] = $value;
				unset($paperContent[$key]);  //把第一级去掉，剩下第二级
			}
		}
		//dump($paperContent);die();
		/*对第二级汉字编号*/
		$num =1;
		foreach ($paperContent as $key=>$value)
		{
			switch ($num){
				case 1 :$paperContent[$key]['hanzi'] ="一"; break;
				case 2 :$paperContent[$key]['hanzi'] ="二"; break;
				case 3 :$paperContent[$key]['hanzi'] ="三"; break;
				case 4 :$paperContent[$key]['hanzi'] ="四"; break;
				case 5 :$paperContent[$key]['hanzi'] ="五"; break;
				case 6 :$paperContent[$key]['hanzi'] ="六"; break;
				case 7 :$paperContent[$key]['hanzi'] ="七"; break;
				case 8 :$paperContent[$key]['hanzi'] ="八"; break;
				default:$paperContent[$key]['hanzi'] ="九";
			}
			$num++;
		}
		foreach ($oneLevel as $key=>$value)
		{
			$tempArray =array();
			foreach ($paperContent as $key2=>$value2)
			{
				if($value['Id'] == $value2['ParentId'])
				{
					$tempArray[] = $value2;
				}
			}
			$oneLevel[$key]['two'] =$tempArray;   //拿到卷体。等待题型中的题目。。
		}
		
		$this->paperHeader =$paperHeader;
		$this->paperContent = $oneLevel;
		$this->render('',false);
	}
	
	/**
	 * 保存试卷设置的对话框。保存。
	 * 开放性：全局开放<br/>
	 */
	function savePaper(){
		$title=$this->spArgs('title');
		$isShow=$this->spArgs('isShow');
		$IsShowScore=$this->spArgs('IsShowScore');
		$QDesc=$this->spArgs('QDesc');		
		$data=$this->QuestionExamCategoryLib->setCategorys($title,$isShow,$QDesc,$IsShowScore);
		if($data)
		{
			echo "success";
		}else{
			echo "false";
		}		
	}
	
	/**
	 * 得到题目的详细，包括题目，答案，解析
	 * 开放性：全局开放<br/>
	 */
	function getAnswer(){
		$questionId = $this->spArgs("questionId");
	    $question =$this->QuestionExamQuestionLib->getDetailQuestion($questionId);     
	    $this->question = $question;
	    $this->render('',false);		
	}
	

	/**
	 * 标定分数弹出查询数据<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=paper&a=getCategoryMsg<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getCategoryMsg(){
		//获取试题
		$examId=$this->spArgs("examId");
		
		//获取学科类型id
		$categroyId=$this->spArgs("categoryId");
		
		//获取当前条目id
		$id=$this->spArgs("id");

		$examMsg=$this->questionExamQuestionLib->getCateQuestion($examId,$categroyId);
		
		$this->cateId=$id;		
		foreach ($examMsg as $key=>&$value)
		{
			$value['sort'] = $key+1;
		}		
		$this->examMsg=$examMsg;
		
		$this->render("",false);
	}
	
	/**
	 * 存储试题分数<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=paper&a=addNum<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function addNum(){
		//试题信息
		$examMsg=$this->spArgs("examMsg");
		
		//考试点ID
		$categoryId=$this->spArgs("CategoryId");
		//测试，写入session
		
		//比较每小题分数是否相等标志
		$flag=false;
		$num=1;
		$firstVal=0;
		foreach ($examMsg as $key => $value) {
			if($key!='total'){
				if($num==1){
					$firstVal=$value;
					$flag=true;
				}else{
					if ($flag){
						$flag=($value==$firstVal);
					}
				}
				$num++;
				
				//循环更新试题分数
				$this->questionExamQuestionLib->mark($key,$value);
			}
		}
		
		$qDesc="本大题共".($num-1)."题,".($flag?"每小题".$firstVal."分,":"")."共".$examMsg['total']."分";
		
		$this->questionExamCategoryLib->mark($categoryId,$qDesc);
		echo $qDesc;
		
	}
	
	/**
	 * 题目上移下移<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=paper&a=order<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function order(){
		//试题信息
		$question=$this->spArgs("questionId");
		//移动标记 0上移，1下移
		$flag=$this->spArgs("flag");
		$this->questionExamQuestion->orderQuestion($question,$flag);
	
	}
	
	/**
	 * 根据试题id删除试题
	 * @param int $questionId
	 */
	public function deleteQuestion($questionId){		
		$questionId = $this->spArgs("questionId");
	    $question =$this->QuestionExamQuestionLib->deleteQuestion($questionId);  
	    echo "success";	    
	} 
	
	/**
	 * 清空一个题型的所有题目
	 * @param int $examId
	 * @param int $categoryId
	 */
	public function clearQuestion(){
		$examId = $this->spArgs("examId");
		$categoryId = $this->spArgs("categoryId");
		$question =$this->QuestionExamQuestionLib->clearQuestionByCategory($examId,$categoryId);
	}
	
	/**
	 * 删除一个题型及其题目
	 * @param int $examId
	 * @param int $categoryId
	 */
	public function deleteCategoryQuestion(){
		$examId = $this->spArgs("examId");
		$categoryId = $this->spArgs("categoryId");		
		
		dump($examId);dump($categoryId); die();
		$this->QuestionExamCategoryLib->deleteCategoryQuestion($examId,$categoryId);	
	}
	

	
}