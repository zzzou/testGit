<?php
/**
 * 协作命题用户
 * 
 * @name 协作命题用户
 * @package PJHome
 * @category model
 * @link /model/m_proposition_user.php
 * @author lpsong
 * @version 1.0
 */
class PropositionUserModel extends  BaseModel{
	//主键
	public $pk = 'id';
	
	//表名
	public $table = 'proposition_user';

    /**
     * 获取用户被分配的所有命题任务
     *
     * @param int $userId 用户id int $propositionId 题目id
     * @return array 命题任务数组
     */
    public function findAllByUserIdAndPropositionIdAndType($userId, $propositionId,$type){
        $sql = "SELECT id
			FROM proposition_user pu
			WHERE pu.user_id=$userId  AND pu.proposition_id=$propositionId  AND pu.type=$type";
        return $this->findSql($sql);
    }

    /**
     * 获取所有命题相关用户
     *
     * @param int $propositionId
     * @param int $type
     * @return array
     */
    public function findAllByPropositionAndType($propositionId, $type){
        return $this->findAll(array('proposition_id'=>$propositionId, 'type'=>$type));
    }

    /**
     * 获取所有命题任务题型信息
     *
     * @param int $propositionId
     * @return array
     */
    public function findAllByProposition($propositionId){
        return $this->findAll(array('proposition_id'=>$propositionId));
    }
}