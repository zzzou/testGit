<?php
/**
 * 协作命题题目
 * 
 * @name 协作命题题目
 * @package PJHome
 * @category model
 * @link /model/m_proposition_item_topic.php
 * @author lpsong
 * @version 1.0
 */
class PropositionItemTopicModel extends  BaseModel{
	//主键
	public $pk = 'id';
	
	//表名
	public $table = 'proposition_item_topic';

    public function updateTopic($propositionTopic)
    {
        $result =$this->update(
            array('id'=>$propositionTopic['id']),
            $propositionTopic);
        return $result;
    }

    /**
     * 获取用户被分配的所有审核题目
     *
     * @param int $propositionItemId itemId
     * @return array 命题任务数组
     */
    public function findPropositionItemTopic($propositionItemId){
        $sql = "SELECT pi.count,pit.id,pit.topic_type,pit.topic_difficulty,pit.topic_knowledge_points,pit.topic_content,pit.state,pit.video
		FROM proposition_item pi LEFT JOIN proposition_item_topic pit ON pi.id=pit.proposition_item_id
		WHERE pi.id=$propositionItemId  ORDER  BY pit.id asc";
        return $this->findSql($sql);
    }

    /**
     * 更新审核题目的状态
     *
     * @param int $id 题目id
     * @return array 命题任务数组
     */
    public function updatePropositionItemTopicById($id,$state){
        $this->updateField(array('id'=>$id), 'state', $state);
    }

    /**
     * 更新审核题目的状态和不通过理由
     *
     * @param int $id 题目id $state state
     * @return array 命题任务数组
     */
    public function updateProTopicStateReasonById($id,$state,$reason){
        $this->update(array('id'=>$id), array(
            'state'	=> $state,
            'reason' => $reason,
        ));
    }

    /**
     * 获取用户被分配的所有审核题目
     *
     * @param int $propositionTeaItemId teaItemId
     * @return array 命题任务数组
     */
    public function findPropositionTeaItemTopic($propositionTeaItemId){
        $sql = "SELECT pi.count,pi.audited_count,pit.id,pit.topic_type,pit.topic_difficulty,pit.topic_knowledge_points,pit.topic_content,pit.state,pit.video,pit.topic_answer
		FROM proposition_teacher_item pi LEFT JOIN proposition_item_topic pit ON pi.id=pit.proposition_teacher_item_id
		WHERE pi.id=$propositionTeaItemId  ORDER  BY pit.id asc";
        return $this->findSql($sql);
    }
    
    /**
     * 更新已命题题目数量
     *
     * @param int $proposition_teacher_item_id
     */
    public function updateAssignedCount($proposition_teacher_item_id){
        $count=$this->findCount(array(
            'proposition_teacher_item_id'=>$proposition_teacher_item_id));
        
        $propositionTeacherItemModel = spClass("PropositionTeacherItemModel");
        $propositionTeacherItemModel->updateField(array('id'=>$proposition_teacher_item_id), 'assigned_count', $count);
    }

    protected function afterDelete($data){;
        $count = $this->findCount(array(
            'proposition_teacher_item_id'=>$data['proposition_teacher_item_id']
        ));

        $propositionTeacherItemModel = spClass("PropositionTeacherItemModel");
        $propositionTeacherItemModel->updateField(array('id'=>$data['proposition_teacher_item_id']), 'assigned_count', $count);
    }
	
}