<?php
/**
 * 协作命题
 * 
 * @name 协作命题
 * @package PJHome
 * @category model
 * @link /model/m_proposition.php
 * @author lpsong
 * @version 1.0
 */
class PropositionModel extends  BaseModel{
	//主键
	public $pk = 'id';
	
	//表名
	public $table = 'proposition';

    /**
     * 获取用户创建的所有命题任务
     *
     * @param int $userId 用户id
     * @param int $state 状态
     * @return array 命题任务数组
     */
    public function findAllByUser($userId){
        return $this->findSql("SELECT * FROM proposition WHERE user_id=$userId AND state>0 ORDER BY id DESC");
    }

    /**
     * 获取用户创建的所有命题任务
     *
     * @param int $userId 用户id
     * @param int $state 状态
     * @return array 命题任务数组
     */
    public function findAllByUserAndStatus($userId, $state=0){
        return $this->findAll(array('user_id'=>$userId, 'state'=>$state), 'id DESC');
    }

    /**
     * 获取用户被分配的所有命题任务
     *
     * @param int $userId 用户id
     * @param int $type 类型
     * @return array 命题任务数组
     */
    public function findAllByUserAndType($userId, $type=1){
        $sql = "SELECT proposition.*
			FROM proposition_user LEFT JOIN proposition ON proposition_user.proposition_id=proposition.id
			WHERE proposition_user.user_id=$userId AND proposition_user.type=$type AND proposition.state>0
			ORDER BY proposition.id DESC";
        return $this->findSql($sql);
    }

    /**
     * 删除未处理的命题任务
     * @param int $id proposition主键
     * @param int $type 类型
     * @return id 删除记录条数
     */
    public function deleteAllByType($id){
        $sql="DELETE FROM proposition WHERE id=$id";
        return $this->findSql($sql);
    }

    /**
     * 获取用户被分配的所有命题任务
     *
     * @param int $userId 用户id
     * @return array 命题任务数组
     */
    public function findTopicTaskByUser($userId){
        $sql = "SELECT proposition_teacher_item.*, proposition.*,
				proposition_teacher_item.state as proposition_teacher_item_state,
				proposition_teacher_item.knowledge_points as proposition_teacher_item_knowledge_points,
				proposition.state as proposition_state, proposition_teacher_item.id as proposition_teacher_item_id
			FROM proposition_teacher_item LEFT JOIN proposition ON proposition_teacher_item.proposition_id=proposition.id
			WHERE proposition.state>1 AND proposition_teacher_item.user_id=$userId
			ORDER BY proposition.id DESC";
        return $this->findSql($sql);
    }

    /**
     * 获取预览页面信息
     *@param int $userId 用户id
     *@return array 预览任务数组
     */
    public function findPreviewById($id){
        $sql="SELECT proposition_item.* ,proposition.*,proposition_teacher_item.*
			  FROM proposition_item
			  LEFT JOIN proposition ON proposition_item.proposition_id=proposition.id
			  LEFT JOIN proposition_teacher_item ON proposition_teacher_item.proposition_id=proposition_item.proposition_id
			  WHERE proposition.id=$id";
        return $this->findSql($sql);
    }

    /**
     * 根据item的id查询题目信息
     * @param $ID proposition_item_id
     * return array 命题类型数组
     */
    public function findPropositionByItemId($id)
    {
        $sql="SELECT pt.* FROM proposition pt where pt.id=(SELECT pi.proposition_id FROM proposition_item pi where pi.id=$id)";
        return $this->findSql($sql);
    }

    /**
     * 更新命题状态为3
     *
     * @param int $proposition_item_id
     * @param int $state 0 1 2 3 4
     */
    public function updatePropositionState($proposition_id,$state)
    {
        $result = $this->update(
            array('id'=>$proposition_id),
            array('state'=>$state)
        );
        return $result;
    }

    /**
     * 更新命题状态为1
     *
     * @param int $proposition_item_id
     * @param int $state 0 1 2 3 4
     */
    public function updateState($id,$state)
    {
        return $this->updateField(array('id'=>$id), 'state', $state);
    }

    /**
     * 更新审核过命题的状态和时间
     *
     * @param  $id 题目id $state state $time time
     * @return array 命题任务数组
     */
    public function updatePropositionStateTimeById($id,$state,$time){
        return $this->update(array('id'=>$id), array(
            'state'	=> $state,
            'audit_time' => $time,
        ));
    }
    
    /**
      * 获取用户被分配的所有审核任务
      *
      * @param int $userId 用户id
      * @return array 命题任务数组
      */
    public function findAuditTaskByUser($userId){
        $sql = "SELECT proposition.*
            FROM proposition LEFT JOIN proposition_user ON proposition_user.proposition_id=proposition.id
            WHERE proposition.state>2 AND proposition_user.user_id=$userId AND proposition_user.type=3
            ORDER BY proposition.id DESC";
        $propositions = $this->findSql($sql);

        $propositionTeacherItem = spClass("PropositionTeacherItemModel");

        foreach ($propositions as $key => $proposition) {
            $teacherItems = $propositionTeacherItem->findAll(array('proposition_id'=>$proposition['id']));
            $propositions[$key]['teacherItems'] = $teacherItems;
            $sum_audited_count = 0;
            $sum_count = 0;
            foreach ($teacherItems as $teacherItem) {
                $sum_audited_count += $teacherItem['audited_count'];
                $sum_count += $teacherItem['count'];
            }

            $propositions[$key]['count'] = $sum_count;
            $propositions[$key]['audited_count'] = $sum_audited_count;
        }

        return $propositions;
    }
	
}