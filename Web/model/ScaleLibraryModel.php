<?php
/**
 * 量表库表
 *
 * @name 量表库表  m_scale_library
 * @package PJHome
 * @category model
 * @link /model/m_scale_library.php
 * @author zzzou
 * @date 2014-4-20  20:35
 * @version 1.0
 */
class ScaleLibraryModel extends  BaseModel{
	//主键
	public $pk = 'id';

	//表名
	public $table = 'scale_library';

    /**
     * 根据评测ID查询记录
     *
     * @param Integer $Id
     * @author lbdai
     * @version 2014-04-28
     * @return 量表库记录
     */
    public function getScaleById($scaleId){
        $result = $this->findSql("
			SELECT sc.id,dim.dim_id,dim.dim_name as dimensionsName,stu.stu_sec_name as phaseName,st.target_name as targetName,sty.type_name as typeName,
            sc.topic_num as topicNum,sc.scale_name as testName,sc.scale_intro as testIntro,sc.use_num as useNum,sc.input_time as testTime,
            sc.test_num as testNum,sc.scale_anw_url,sc.scale_url,sc.stu_sec_id,sc.scale_file_name,sc.scale_anw_file_name,sc.type_id
            FROM scale_library as sc LEFT JOIN dimension as dim ON dim.dim_id=sc.dim_id
            LEFT JOIN studying_section as stu ON stu.stu_sec_id=sc.stu_sec_id
            LEFT JOIN scale_target as st ON st.target_id=sc.target_id
            LEFT JOIN scale_type as sty ON sty.type_id = sc.type_id
            WHERE  id = $scaleId");

        return $result;

    }

    /**
     * 根据删选条件查询量表
     *
     * @param $condition 删选条件
     * @param $page 当前页数
     * @author zzzou
     * @date 2014-5-13
     * @return array
     */
    public function findByCondition($page,$condition){
        $whereCondition = "0=0 ";

        //生成查询条件
        if ($condition['typeId']) {
            $whereCondition .= ' AND sc.type_id='.$condition['typeId'];
        }
        if ($condition['dimensionsId']) {
            $whereCondition .= ' AND sc.dim_id = '.$condition['dimensionsId'];
        }
        if ($condition['phaseId']) {
            $phaseId = $condition['phaseId'];
            $whereCondition .= " AND sc.stu_sec_id LIKE '%$phaseId%'";
        }
        if ($condition['targetId']) {
            $whereCondition .= ' AND sc.target_id = '.$condition['targetId'];
        }

        $result = $this->spPager($page, 5);
        $data = $result->findSql("
				SELECT sc.id,dim.dim_name as dimensionsName,stu.stu_sec_name as phaseName,st.target_name as targetName,sty.type_name as typeName,
                sc.topic_num as topicNum,sc.scale_name as testName,sc.scale_intro as testIntro,sc.use_num as useNum,sc.input_time as testTime,
                sc.test_num as testNum,sc.scale_anw_url,sc.scale_url,sc.stu_sec_id
                FROM scale_library as sc LEFT JOIN dimension as dim ON dim.dim_id=sc.dim_id
                LEFT JOIN studying_section as stu ON stu.stu_sec_id=sc.stu_sec_id
                LEFT JOIN scale_target as st ON st.target_id=sc.target_id
                LEFT JOIN scale_type as sty ON sty.type_id = sc.type_id
				WHERE  ".$whereCondition);

        $page = $this->spPager()->getPager();
        $page['data'] = $data;

        return $page;
    }

    /**
     * 查询出所有评价维度
     * @author zzzou
     * @date 2014-04-25
     */
    public function getDimsions(){

        return $this->findSql("
				SELECT dim_id,dim_name
				FROM dimension
				WHERE dim_id != 1");
    }

    /**
     * 根据评价维度，查询评价指标
     *
     * @param int $dimId 评价维度id
     * @author zzzou
     * @date 2014-04-25
     */
    public function getTargetByDim($dimId){
        return $this->findSql("
				SELECT target_id,target_name
				FROM scale_target
				WHERE dim_id = $dimId");
    }


    /**
     * 查询所有学段（除了全部）
     *
     * @author zzzou
     * @date 2014-04-25
     * @return mixed
     */
    public function getStuSec(){
        return $this->findSql("
				SELECT stu_sec_id,stu_sec_name
				FROM studying_section
				WHERE stu_sec_id != 1");
    }

    /**
     * 根据量表名查询对应学段
     *
     * @param string $scaleName 量表名
     * @author zzzou
     * @date 2014-04-25
     * @return array
     */
    public function getStuSecByScale($scaleName){
        $re =  $this->findSql("
				SELECT sl.stu_sec_id
				FROM scale_library sl
				WHERE sl.scale_name = '$scaleName' ");
        $phaseIdArr = explode(",", $re[0]['stu_sec_id']);

        return $phaseIdArr;
    }

    /**
     * 修改量表
     *
     * @param array $scale 量表数组
     * @author zzzou
     * @date 2014-04-25
     * @return false | scale_id  已存在|修改成功
     */
    public function updateScale($scale){
        $scaleRe = $this->find(array("scale_name"=>$scale['scale_name']));
        if ($scaleRe!=null && $scaleRe['id'] != $scale['scale_id']) {
            return "false";
        }else {
            return $this->update(array("id"=>$scale['scale_id']),$scale);
        }

    }

    /**
     * 添加量表库
     *
     * @param array $scale 量表数组
     * @author zzzou
     * @date 2014-04-25
     * @return boolean false|其他，量表库中存在相同的数据|插入成功
     */
    public function addScal($scale){
        $scaleRe = $this->find(array("scale_name"=>$scale['scale_name']));
        if ($scaleRe!=null) {
            return "false";
        }else {
            return $this->create($scale);
        }
    }

    /**
     * 根据评价指标id查询评价指标名称
     *
     * @param int $targetId 评价指标id
     * @author zzzou
     * @date 2014-04-25
     */
    public function getTargetName($targetId){
        return $this->findSql("
				SELECT target_name
				FROM scale_target
				WHERE target_id = $targetId");
    }

    /**
     * 根据量表id删除量表库数据
     *
     * @param int $scaleId 量表id
     * @author zzzou
     * @date 2014-04-25
     */
    public function deleteScale($scaleId){
        $this->delete(array("id"=>$scaleId));
    }

    /**
     * 根据学段id查找学段名称
     *
     * @param int $phaseId 学段id
     */
    public function getPhaseNameById($phaseId){
        return $this->findSql("
				SELECT stu_sec_name
				FROM studying_section
				WHERE stu_sec_id = $phaseId");
    }

    /**
     * 查询所有学段
     *
     * @author zzzou
     * @date 2014-04-25
     * @return mixed
     */
    public function getAllPhase(){
        return $this->findSql("
                            SELECT stu_sec_id,stu_sec_name
                            FROM studying_section");
    }

    /**
     * 查询所有维度
     *
     * @author zzzou
     * @date 2014-04-25
     * @return mixed
     */
    public function getAllDim(){
        return $this->findSql("
                            SELECT dim_id,dim_name
                            FROM dimension");
    }

    /**
     * 查询所有方式
     *
     * @author zzzou
     * @date 2014-04-25
     * @return mixed
     */
    public function getAllType(){
        return $this->findSql("
                            SELECT type_id,type_name
                            FROM scale_type");
    }


}
/* End of file m_word.php */
/* Location: ./model/m_word.php */