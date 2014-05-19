<?php
require_once(MODEL_PATH.'/DictSchemaModel.php');
/**
 * 
* 表question_examQuestion操作
* @package lib
* @link /lib/QuestionExamQuestionLib.php
* @author qiancheng
* @data 2014-05-13
*
* @history
*/
class QuestionExamQuestionLib extends BaseLib{
	protected  $modelName = 'QuestionExamQuestionModel';

	
	/**
	 * 对试题移动排序
	 * @param Integer $questionId 试题id
	 * @param Integer flag 移动标志，0，上移；1，下移
	 */
	public function orderQuestion($questionId,$flag){
		//获取试题对应题型的所有排序信息
		$orderMsg=$this->getOrder($questionId);
		//当前试题所在的列
		$orderNow=-1;
		if(!empty(array_filter($orderMsg))){
			foreach ($orderMsg as $key=> $value) {
				if($value['Id']==$questionId){
					$orderNow=$key;
					break;
				}
			}
			
			switch (count($orderMsg)){
				case 1:
					//不存在上移下移
					break;
				case 2:
					//只能上移或者下移
					if($flag==0&&$orderNow==1){
						//更新上下排序
						$this->updateOrder($orderMsg[0]['Id'],$orderMsg[1]['sortOrder']);
						$this->updateOrder($orderMsg[1]['Id'],$orderMsg[0]['sortOrder']);
					}else if($flag!=0&&$orderNow==0){
						$this->updateOrder($orderMsg[0]['Id'],$orderMsg[1]['sortOrder']);
						$this->updateOrder($orderMsg[1]['Id'],$orderMsg[0]['sortOrder']);
					}
					break;
				default:
					//根据需要移动的试题所在位置判断移动	
					if($flag==0&&$orderNow!=0){
						$this->updateOrder($orderMsg[$orderNow]['Id'],$orderMsg[$orderNow-1]['sortOrder']);
						$this->updateOrder($orderMsg[$orderNow-1]['Id'],$orderMsg[$orderNow]['sortOrder']);
					}else if($flag==1&&$orderNow!=(count($orderMsg)-1)){
						$this->updateOrder($orderMsg[$orderNow]['Id'],$orderMsg[$orderNow+1]['sortOrder']);
						$this->updateOrder($orderMsg[$orderNow+1]['Id'],$orderMsg[$orderNow]['sortOrder']);
					}
					break;
			}
		}

	}
	
	
	/**
	 * 获取题目的详细信息
	 * @param int $id
	 */
	public function getDetailQuestion($id){
		$question=$this->getQuestion($id);
		$bankId=$question['BankId'];
		$categoryId =$question['CategoryId'];
	
		$dictSchemaModel = new DictSchemaModel();
		$question['categoryTitle']=$dictSchemaModel->getCategoryTitle($bankId,$categoryId);
	
		return $question;
	}
	
	
}