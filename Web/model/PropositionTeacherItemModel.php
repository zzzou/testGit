<?php
/**
 * 协作命题老师任务
 * 
 * @name 协作命题老师任务
 * @package PJHome
 * @category model
 * @link /model/m_proposition_teacher_item.php
 * @author lpsong
 * @version 1.0
 */
class PropositionTeacherItemModel extends  BaseModel{
	//主键
	public $pk = 'id';
	
	//表名
	public $table = 'proposition_teacher_item';

    /**
     * 更新教师命题状态为1
     *
     * @param int $proposition_teacher_item_id
     * @param int $state 0 1
     */
    public function updateState($proposition_teacher_item_id,$state)
    {
        return $this->updateField(array('id'=>$proposition_teacher_item_id), 'state', $state);
    }

    /**
     * 更新命题审核数量
     *
     * @param int $proposition_teacher_item_id
     * @param int $state 0 1
     */
    public function updateAuditedCount($proposition_teacher_item_id,$audited_count)
    {
        return $this->updateField(array('id'=>$proposition_teacher_item_id), 'audited_count', $audited_count);
    }


}