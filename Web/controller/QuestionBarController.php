<?php

/**
 * 试题栏控制器
 *
 * @package controller
 * @link /controller/QuestionController.php
 * @author lbdai
 * @date 2014/05/09
 */

class QuestionBarController extends BaseController{

	
	/**
	 * 加入试题栏<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=QuestionBar&a=joinQuesBar<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function joinQuesBar(){
		//获取题号
		$id=$this->spArgs("id");
		$ids=explode(",",$id);
		$quesBar=array();
		//判断session是否存在试题栏,不存在创建，存在查询出
		if(!isset($_SESSION["questionBar"])){
			$quesBar=$this->joinSession();
		}else{
			$quesBar=$_SESSION['questionBar'];
		}
		
		//循环修改试题栏信息
		
		foreach ($ids as $value1) {
			
			//查询出试题信息修改
			$questionMsg=$this->questionLib->findByPk($value1);
			
			//判断试题是否在试题库
			foreach ($quesBar['list'] as $key=>  &$value) {
				//找对试题对应题型
				
				if($value['Name']==$questionMsg['CategoryId']){
					
					$sign=false;
					foreach ($value['listTitle'] as $value2) {
						if($value2['id'] == $questionMsg['Id']){
							$sign=true;
						}
					}

					//判断试题是否存在
					if(!$sign){
						//试题不存在
						$title=array();
						$title['id']=$questionMsg['Id'];
						$title['sourceId']=$questionMsg['SourceId'];
						$title['content']=$questionMsg['BodyImg'];
						$value['listTitle'][] = $title;
						//修改题目总数，类型总数，比例
						$value['num']++;
						$quesBar['sum']++;
						$value['scale']=$value['num']/$quesBar['sum'];
						//插入数据库
						$this->updateDB($questionMsg, 1);
					}
				}
				$value['Title']=$value['note'].'('.$value['num'].')';
				
			}
			
		}
		
		$_SESSION["questionBar"]=$quesBar;
		
		
	}
	
	/**
	 * 操作数据
	 * @param array $questionMsg 需要在数据库操作的数据
	 * @param integer $flag 1: 插入; 2:删除
	 */
	private function updateDB($questionMsg,$flag){
		//默认学科为语文，等待修改
		$bankId=10;
		//获取用户信息
		$userId=$this->getSessionUserId();
		//判断试题在试题栏表中是否存在
		$cartArray=$this->cartQuestionLib->findAllByUserIdAndQuestionId($userId,$questionMsg['Id']);
		
		switch ($flag){
			case 1://插入
				//获取知识点
				$knowledgeList=$this->questionKnowledgeLib->findAllBySourceQuestionIdAndCate($questionMsg['Id'],2);
				$knowledgeIds='';
				foreach ($knowledgeList as $value) {
					if($knowledgeIds=='')
						$knowledgeIds=$value['KnowledgeId'];
					else
						$knowledgeIds=$knowledgeIds.','.$value['KnowledgeId'];
				}
				//从字典表查询出难易程度对应字段
				$diffName='';
				$diff=$this->dictSchemaLib->getDictByCate($bankId,"diff",0);
				foreach($diff as $value){
					if($value['Name']==$questionMsg['DiffId']){
						$diffName=$value['Title'];
						break;
					}
				}
				
				//从字典表查询出难易程度对应字段
				$typeName='';
				$diff=$this->dictSchemaLib->getDictByCate($bankId,"examtype",3);
				foreach($diff as $value){
					if($value['Name']==$questionMsg['TypeId']){
						$typeName=$value['Title'];
						break;
					}
				}
				
				//从字典表查询出难易程度对应字段
				$categoryName='';
				$diff=$this->dictSchemaLib->getDictByCate($bankId,"cate",1);
				foreach($diff as $value){
					if($value['Name']==$questionMsg['CategoryId']){
						$categoryName=$value['Title'];
						break;
					}
				}
				
				$data=array(
				'diffId'=>$questionMsg['DiffId'],
				'typeId'=>$questionMsg['TypeId'],
				'categoryId'=>$questionMsg['CategoryId'],
				'userId'=>$userId,
				'diffName'=>$diffName,
				'typeName'=>$typeName,
				'categoryName'=>$categoryName,
				'bankId'=>$questionMsg['BankId'],
				'questionId'=>$questionMsg['Id'],
				'paperTitle'=>$questionMsg['PaperTitle'],
				'body'=>$questionMsg['Body'],
				'dateCreated'=>$questionMsg['DateCreated'],
				'sourceQuestionId'=>$questionMsg['SourceId'],
				'parse'=>$questionMsg['Parse'],
				'answer'=>$questionMsg['Answer'],
				'bodyImg'=>$questionMsg['BodyImg'],
				'parseImg'=>$questionMsg['ParseImg'],
				'answerImg'=>$questionMsg['AnswerImg'],
				'knowledgeIds'=>$knowledgeIds
				);

				$this->CartQuestionLib->addQuestion($data);
				break;
			case 2://删除
				if(!empty($cartArray)){
					$this->CartQuestionLib->delQuestion(array('UserId'=>$userId,'QuestionId'=>$questionMsg['Id']));
				}
				break;
				
		}
		
		
	}
	
	/**
	 * 判断试题是否已经在试题栏数据库<br/>
	 * 
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=QuestionBar&a=isInQuestionBar<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function isInQuestionBar(){
		//获取用户信息
		$userId=$this->getSessionUserId();
		//获取题目信息
		$id=$this->spArgs("quesId");
		$tmp =$this->CartQuestionLib->findAllByUserIdAndQuestionId($userId,$id);
		$isEmpty=empty($tmp);
		if(empty($tmp)){
			$this->renderJSON($this->buildResult(1));//数据不存在
		}else{
			$this->renderJSON($this->buildResult(0));//数据存在
		}
		
	}
	
	/**
	 * 从试题栏中删除<br/>
	 * 
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=QuestionBarController&a=delQuesBar<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function delQuesBar(){
		//获取题号
		$id=$this->spArgs("id");
		$ids=explode(",",$id);
		$quesBar=array();
		//判断session是否存在试题栏,不存在创建，存在查询出
		if(!isset($_SESSION["questionBar"])){
			$quesBar=$this->joinSession();
		}else{
			$quesBar=$_SESSION['questionBar'];
		}
		//循环修改试题栏信息
		
		foreach ($ids as $value1) {
			//查询出试题信息修改
			$questionMsg=$this->questionLib->findByPk($value1);
			//判断试题是否在试题库
			foreach ($quesBar['list'] as &$value) {
				//找对试题对应题型
				if($value['Name']==$questionMsg['CategoryId']){
					//判断试题是否存在
					
					for ($i = 0; $i < count($value['listTitle']); $i++) {
						
						if($value['listTitle'][$i]['id']== $questionMsg['Id']){
							//试题已经存在
							array_splice($value['listTitle'],$i,1);
							//从数据库删除
							$this->updateDB($questionMsg, 2);
							//修改题目总数，类型总数，比例
							$value['num']--;
							$quesBar['sum']--;
							$value['scale']=$value['num']/$quesBar['sum'];
						}
					}
					
				}
				$value['Title']=$value['note'].'('.$value['num'].')';
			}
				
		}
		$_SESSION["questionBar"]=$quesBar;


		
	}
	
	/**
	 * 获取试题栏信息<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=QuestionBar&a=getQuesBar<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getQuesBar(){
		
			
		//判断session是否存在试题栏
		if(!isset($_SESSION["questionBar"])){
			 $this->renderJSON($this->joinSession());
		}else{
			 $this->renderJSON($_SESSION['questionBar']);
		}	

	}
	
	/**
	 * 从数据库查询出试题栏信息
	 * @param integer $bankId 对应的题型，默认语文
	 * @return array 试题栏信息
	 */
	private function joinSession($bankId=10){
		//获取用户信息
		$userId=$this->getSessionUserId();
		
		$questionBar=array();
		$questionBar["sum"]=0;
		//获取题型信息
		$data=$this->dictSchemaLib->getDictByCate($bankId,"cate",1);

		//获取用户在cart_question表中存储数据
		$cartArray=$this->CartQuestionLib->getCartQuestionById($userId);
		//判断是否为空
		$sign=empty($cartArray);
		
		foreach ($data as  $key=> $value) {
			$list=array();
			$list['num']=0;
			$list['scale']=0;
			$list['listTitle']=array();
			if(!$sign){
				foreach ($cartArray as $value1) {
					//找出对应的题型
					if($value1['CategoryId']==$value['Name']){
						$title=array();
						$title['id']=$value1['QuestionId'];
						$title['sourceId']=$value1['SourceQuestionId'];
						$title['content']=$value1['BodyImg'];
						$list['listTitle'][]=$title;
						//修改对应题型比例
						$questionBar["sum"]++;
						$list['num']++;
						$list['scale']=$list['num']/$questionBar["sum"];
					}
				}
			}


			$list['Name']=$value['Name'];
			$list['note']=$value['Title'];
			$list['Title']=$value['Title']."(".$list['num'].")";

			$questionBar['list'][$key]=$list;
		}
		
		$_SESSION["questionBar"] = $questionBar;
		
		return $questionBar;
	}
	
}

?>