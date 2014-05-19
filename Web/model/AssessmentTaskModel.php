<?php
/**
 * 协作命题
 * 
 * @name 协作命题
 * @package PJHome
 * @category model
 * @link /model/m_assessment_task.php
 * @author lbdai
 * @version 1.0
 */
class AssessmentTaskModel extends  BaseModel{
	//主键
	public $pk = 'id';
	
	//表名
	public $table = 'assessment_task';
    
    /**
     * 获取用户创建的所有测评任务
     *
     * @param int $userId 用户id
     * @return array 命题任务数组
     */
    public function findAllByUserAndStatus($userId){
        return $this->findAll(array('user_id'=>$userId), 'id DESC');
    }

    /**
     * 获取用户创建的所有测评任务（关联量表的下载和预览信息）
     * @param int $userId 用户id
     * @return array 测评任务数组
     */
    public function findAllByUser($userId){

        return $this->findSql("
				SELECT ass.*,sc.scale_url,sc.scale_anw_url,sc.test_num,sc.scale_name
				FROM assessment_task as ass
				LEFT JOIN scale_library as sc
				ON ass.scale_id=sc.id
				WHERE ass.user_id='$userId'");

    }
	
}