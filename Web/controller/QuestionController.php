<?php
/**
 * 题目控制器
 *
 * @package controller
 * @link /controller/QuestionController.php
 * @author lpsong
 */
require_once(UTIL_PATH . '/TopicUtil.php');

class QuestionController extends BaseController{
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct ();
		$this->modelID = 18;
	}
	
	/**
	 * 格式化题型 汉字显示
	 * @param 数据库中查询出来的  $problemType
	 * @param 题型编号  $name
	 * @return 题型的汉字
	 */
	
    public	function dealProblemType($problemType,$name){
		foreach ($problemType as $key=>$value)
		{
			if($value['Name'] == $name)
			{
				return $value['Title'];
			}
		}
	}
	/**
	 * 新建我的题库题目<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=create</br>
	 * author qifang
	 * 方法说明：默认进入新建试题页面<br/>
	 * 提交方式：get<br/>
	 */
	function create(){
		$body=$this->spArgs('body');
		$answer=$this->spArgs('answer');
		$parse=$this->spArgs('parse');
		//判断参数
		if(empty($body)&&empty($body)&&empty($body)){
			$this->render();
		}else{
			$question = array(
				'schoolId'=>"4wg3ajgiyrnjfhbx2jcqrq",
				'userId'=>"7oogamcif4rb5xjyuleafg",
				'body'=>$body,
				'answer'=>$answer,
				'parse'=>$parse
			);
			$data=$this->questionLib->addNewQuestion($question);
			if($data){
				$this->redirect(array(
					'c'=>'question',
					'a'=>'index'
				));
			}else{
				echo "服务器正在维护，请稍后再试";
			}
		}
	}

	/**
	 * 修改我的题库题目<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=edit</br>
	 * author qifang
	 * 方法说明：默认进入修改试题页面<br/>
	 * 提交方式：get<br/>
	 */
	function edit(){
		
		$id = $this->spArgs('id');
		if(!$id){
			$this->notFound();
		}else{
			$data=$this->questionLib->findByPk($id);
			$this->question=$data;
			$this->id=$id;
			$this->render();
		}
		
	}
	
	/**
	 * 更新我的题库题目<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=update</br>
	 * author qifang
	 * 方法说明：默认进入修改试题页面<br/>
	 * 提交方式：get<br/>
	 */
	function update(){
		$id=$this->spArgs('id');
		$body=$this->spArgs('body');
		$answer=$this->spArgs('answer');
		$parse=$this->spArgs('parse');
		if(!$id){
			$this->notFound();
		}else{
			$question = array(
					'id'=>$id,
					'userId'=>"7oogamcif4rb5xjyuleafg",
					'body'=>$body,
					'answer'=>$answer,
					'parse'=>$parse
			);
			$data=$this->questionLib->editQuestion($question);
			if(!empty($data)){
				$this->getMyquestion();
				$this->render('question/index');
			}else{
				echo "服务器正在维护，请稍后再试";
			}
			
		}
	}
	
	/**
	 * 删除我的题库题目<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=delete</br>
	 * author qifang
	 * 方法说明：默认进入我的题库页面<br/>
	 * 提交方式：get<br/>
	 */
	function delete(){
		$id=$this->spArgs('id');
		if(!$id){
			$this->notFound();
		}else{
			$data=$this->questionLib->deleteByPk($id);
			if(!$data){
				$this->redirect(array(
						'c'=>'question',
						'a'=>'index'
				));
			}else{
				echo "服务器正在维护，请稍后再试";
			}
		}
	}
	
	/**
	 * 参考题库<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=referQuestion</br>
	 * @author wqyan
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function referQuestion() {
		$this->bank = "高中语文";
		$this->bankSort ="高中语文综合库";
		$this->problemTypes=$this->dictSchemaLib->getDictByCate(10,"cate",1);
		$this->difficult = $this->dictSchemaLib->getDictByCate(10,"diff",0);
		$this->year=$this->dictSchemaLib->getDictByCate(10,"year",0);
		$this->type= $this->dictSchemaLib->getDictByCate(10,"examtype",3);
		$this->grade = $this->dictSchemaLib->getDictByCate(10,"grade",2,3);
		$this->modelID = 21;
		$BankId=10;
		$cateoryMsg=$this->categoryLib->findAllByBankId($BankId);
		$this->data =$cateoryMsg;	
		$this->render();
	}
	/**
	 * 我的题库<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=index</br>
	 * author qifang
	 * 方法说明：进入我的题库页面<br/>
	 * 提交方式：get<br/>
	 */
	function index(){
		$this->getMyquestion();
		$this->render();
	}
	
	/**
	 * 我的题库子页面<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=myQuestion</br>
	 * author qifang
	 * 方法说明：我的题库子页面用于查询题目<br/>
	 * 提交方式：get<br/>
	 */
	function myQuestion(){
		$this->getMyquestion();
		$this->render('',false);
	}
	
	/**
	 * 获取我的题库题目<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：./index.php?c=question&a=getMyquestion</br>
	 * author qifang
	 * 提交方式：get<br/>
	 */
	function getMyquestion(){
		//获取参数
		$pageIndex = $this->spArgs("page");
		$params["pageSize"] = $this->spArgs("pageSize");
		$bankId = $this->spArgs("bankId");
		$yearNo = $this->spArgs("yearNo");
		
		$categoryId = $this->spArgs("cate_id");
		$gradeId = $this->spArgs("grade_id");
		$typeId=$this->spArgs("typeId");
		$diffId=$this->spArgs("diff_id");
        $signedId = $this->spArgs("IsSigned");
		//目前写死，以后再用户信息里面获取
		$teacherId="7oogamcif4rb5xjyuleafg";
	
		//传入接口参数
		$params=array();
		if (!empty($pageIndex)) {
			$params["pageIndex"]=$pageIndex;
		}else {
			$pageIndex=1;
			$params["pageIndex"]=1;
		}
		if (!empty($pageSize)) {
			$params["pageSize"]=$pageSize;
		}else{
			$params["pageSize"]=10;
		}
		if (!empty($bankId)) {
			$params["bankId"]=$bankId;
		}else {
			$params["bankId"]=10;
		}
		if ($yearNo) {
			$params["yearNo"]=$yearNo;
		}

		if ($categoryId) {
			$params["categoryId"]=$categoryId;
		}
		if ($gradeId) {
			$params["gradeId"]=$gradeId;
		}
		if ($typeId) {
			$params["typeId"]=$typeId;
		}
		if ($diffId) {
			$params["diffId"]=$diffId;
		}

        if ($signedId == "0" || $signedId == "1"){
            $params['IsSigned'] = intval($signedId);
        }
			
		if($teacherId){
			$params['teacherId']=$teacherId;
		}
		//调用接口
		$data=$this->questionLib->getQuestion($params);
		$diffculties = topicGetDiffculties();
		$topicTypes = topicGetTopicTypes();
		//对返回数据做相应处理
		foreach ($data['data'] as $key=>$value) {
			$value['DiffId'] = $diffculties[$value['DiffId']];
			preg_match('/\d+/', $value['DateCreated'], $matches);
			$value['DateCreated'] = strftime('%Y-%d-%m', intval($time));
			$value['CategoryId'] = $topicTypes[$value['CategoryId']];
			$data['data'][$key] = $value;
		}
		$cate=$this->dictSchemaLib->getDictByCate("10","cate",1);
		$diff=$this->dictSchemaLib->getDictByCate("10","diff",0);
		$grade=$this->dictSchemaLib->getDictByCate("10","grade",2,3);
		//输出到模板
		$this->cate=$cate;
		$this->diff=$diff;
		$this->grade=$grade;
		$this->data=$data['data'];
		$this->pageIndex =$pageIndex;
		$this->pageCount =$data['pageCount'];
		$this->count = $data['count'];
	}
	
	/**
	 * 获得参考题库模块的 getQuestion
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=question&a=getQuestion<br/>
	 * 参数说明：无<br/>
	 *  @author wqyan
	 * 提交方式：get<br/>
	 */
	public function getQuestion()
	{
		
		//获取用户信息
		$userId=$this->getSessionUserId();
	
		$this->ifhead ="2";
		$pageIndex = $this->spArgs("pageIndex");
		$pageSize = $this->spArgs("pageSize");
		$bankId =10;
		$yearNo = $this->spArgs("yearNo");
		$knowledgeId = $this->spArgs("knowledgeId");
		$categoryId = $this->spArgs("categoryId");
		$gradeId = $this->spArgs("gradeId");
		$typeId=$this->spArgs("typeId");
		$diffId=$this->spArgs("diffId");
	
		$topicTypes = topicGetTopicTypes();
		$temp = array_flip($topicTypes);
		$params=array();
		
		if ($pageIndex) {
			$params["pageIndex"]=$pageIndex;
		}
		if ($pageSize) {
			$params["pageSize"]=$pageSize;
		}
		if ($bankId) {
			$params["bankId"]=$bankId;
		}
		if ($yearNo) {
			$params["yearNo"]=$yearNo;
		}
		if ($knowledgeId) {
			$params["knowledgeId"]=$knowledgeId;
		}
		if ($categoryId) {
			$params["categoryId"]=$categoryId;
		}
		if ($gradeId) {
			$params["gradeId"]=$gradeId;
		}
		if ($typeId) {
			$params["typeId"]=$typeId;
		}
		if ($diffId) {
			$params["diffId"]=$diffId;
		}
		
		$data=$this->questionLib->getQuestion($params);
		$diffculties = topicGetDiffculties();
		
		$problemTypes=$this->dictSchemaLib->getDictByCate(10,"cate",1);
		foreach ($data['data'] as $key=>$value) {
			$value['DiffId'] = $diffculties[$value['DiffId']];
			preg_match('/\d+/', $value['DateCreated'], $matches);
			$value['DateCreated'] = strftime('%Y-%d-%m', intval($time));
			//$value['CategoryId'] = $topicTypes[$value['CategoryId']];
			$value['CategoryId'] = $this->dealProblemType($problemTypes ,$value['CategoryId']);
			$data['data'][$key] = $value;
		}		
				
		/*userId 先写死  7oogamcif4rb5xjyuleafg*/

		foreach ($data['data'] as $key=>&$value) {
			$yesOrNo = $this->questionLib->isQuote($value['SourceId'],"7oogamcif4rb5xjyuleafg");
			if($yesOrNo == true)
			{
				$value['isQuote'] ="1";
			}else{
				$value['isQuote'] ="0";
			}
			
			//是否已经加入试题栏
			$isBar=$this->cartQuestionLib->findAllByUserIdAndQuestionId($userId,$value['SourceId']);
			if(empty($isBar)){
				$value['isBar']='0';
			}else{
				$value['isBar']='1';
			}
			
		}
		$this->data =$data['data'];
		$this->pageIndex =$pageIndex;
		$this->pageCount =$data['pageCount'];
		$this->count = $data['count'];	
		$this->render('',false);
	}
	
	/**
	 * 参考题库中。。引用到我的题库  
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=question&a=getQuestion<br/>
	 * 参数说明：无<br/>
	 *  @author wqyan
	 * 提交方式：get<br/>
	 */
	function addQuote(){
		
		$SourceId = $this->spArgs("SourceId");
		$userId = $this->spArgs("userId");
		$schoolId = $this->spArgs("schoolId");
		$dataPerson=$this->questionLib->addMyQuestion($SourceId,$userId,$schoolId);
		if($dataPerson ==false)   //保存失败
		{
			buildResult("1", "引用到我的题库保存失败", "");  //$state假设0成功。。到时有一个标准
		}else{
			buildResult("0", "success", "");
		}		
	}
	
    /**
     * 我的题库标定页面
     */
	public function calibration(){
		$id=$this->spArgs('id');
		$BankId=10;
		$this->bank = "高中语文";
		$this->bankSort ="高中语文综合库";
		$this->problemTypes=$this->dictSchemaLib->getDictByCate(10,"cate",1);
		$this->difficult = $this->dictSchemaLib->getDictByCate(10,"diff",0);
		$this->year=$this->dictSchemaLib->getDictByCate(10,"year",0);
		$this->type= $this->dictSchemaLib->getDictByCate(10,"examtype",3);
		$this->grade = $this->dictSchemaLib->getDictByCate(10,"grade",2,3);
		$cateoryMsg=$this->categoryLib->findAllByBankId($BankId);
		$this->data =$cateoryMsg;
		$this->id=$id;
		$this->render();
	}
	
	/**
	 * 保存标定页面数据
	 */
	public function saveCalibration(){
		
		$calibration=$this->spArgs('calibration');
		$id=$this->spArgs('id');
		//目前写死
		$calibration['userId']="7oogamcif4rb5xjyuleafg";
		$data=$this->questionLib->sign(array("id"=>$id),$calibration);
		//跳转
		$this->redirect(array(
						'c'=>'question',
						'a'=>'index'
				));
		
	}
	
	

	
}