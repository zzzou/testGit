<?php
/**
 * 协作命题题目类型
 * 
 * @name 协作命题题目类型
 * @package PJHome
 * @category model
 * @link /model/m_proposition_item.php
 * @author lpsong
 * @version 1.0
 */
class PropositionItemModel extends  BaseModel{
	//主键
	public $pk = 'id';
	
	//表名
	public $table = 'proposition_item';

    /**
     * 根据ID查询所有类型信息
     * @param $ID proposition_id
     * return array 命题类型数组
     */
    public function findAllById($id)
    {
        $sql="SELECT id,proposition_id,state, topic_type,SUM(count) as count FROM proposition_item where proposition_id=$id GROUP BY topic_type";
        return $this->findSql($sql);
    }

    /**
     * 更新命题状态为1
     *
     * @param int $proposition_item_id
     * @param int $state 0 1
     */
    public function updateState($proposition_item_id,$state)
    {
        $this->updateField(array('id'=>$proposition_item_id), 'state', $state);
        $sql="SELECT proposition_id FROM proposition_item WHERE id = $proposition_item_id";
        $proposition_id=$this->findSql($sql);
        $state = true;
        if($proposition_id){
            $proposition_id = $proposition_id['0']['proposition_id'];
            $sql="SELECT state FROM proposition_item where proposition_id=$proposition_id AND state=0";
            $state = $this->findSql($sql);
        }
        $array = array('state'=>$state,'proposition_id'=>$proposition_id);
        return $array;
    }
	
}