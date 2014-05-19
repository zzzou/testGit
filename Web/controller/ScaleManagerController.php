<?php
/**
 * 量表库管理控制器
 *
 * @package controller
 * @link /controller/ScaleManagerController.php
 * @author zzzou
 * @date 2014-04-28
 *
 * @history：
 *
 */
class ScaleManagerController extends BaseController{
	
	/**
	 * 量表库编辑页面</br>
	 * 开放性:全局开放</br>
	 * 调用方式：/index.php?c=scale_manager_controller&a=scaleEditIndex<br/>
	 * 参数说明：无<br/>
	 * @author zzzou
	 * @date 2014-04-28
	 */
	public function scaleEditIndex(){
		$this->modelID = 218;
		
		$scaleId = intval($this->spArgs('scale_id'));
		$scale = $this->scaleLibraryLib->getScaleByid($scaleId);
		//查询所有评价维度
		$scaleDim = $this->scaleLibraryLib->getDimsions();
		//根据评价维度查询评价指标
		$scaleTarget = $this->scaleLibraryLib->getTargetByDim($scale[0]['dim_id']);
		$studySec = $this->scaleLibraryLib->getStuSec();
		$scaleStuSec = $this->scaleLibraryLib->getStuSecByScale($scale[0]['testName']);
		//把查询出的数据转换成smarty模板需要的数据
		$scaleDims=array();
		foreach ($scaleDim as $key=>$value) {
			$scaleDims[$value['dim_id']]=$value['dim_name'];
		}
		
		$scaleTar = array();
		foreach ($scaleTarget as $key=>$value){
			$scaleTar[$value['target_id']] = $value['target_name'];
		}
		
		$stuSecArr = array();
		foreach ($studySec as $key=>$value){
			$stuSecArr[$value['stu_sec_id']] = $value['stu_sec_name'];
		}
		
		$scaleStuSecArr = array();
		foreach ($scaleStuSec as $key=>$value){
			$scaleStuSecArr[] = $value;
		}

		$this->scale=$scale[0];
		$this->answerSheet=$scale[0]['testName']."_答题卡";
		$this->scaleDim=$scaleDims;
		$this->scaleTarget = $scaleTar;
		$this->stuSec = $stuSecArr;
		$this->scaleStuSec = $scaleStuSecArr;
		$this->render("scaleLibraryManager/edit_scale");
	}

    /**
     * 根据学段id获取所有学段名称，并用逗号分开
     * @param array $scale
     * @return string
     */
    private function getPhaseName($scale){
        for ($i=0;$i<count($scale);$i++){

            $scaleUrl = $scale[$i]['scale_url'];
            $urlArr = explode("/", $scaleUrl);
            $scale[$i]['download_name'] = $urlArr[count($urlArr)-1];

            $phaseId = $scale[$i]['stu_sec_id'];
            $phaseArr = explode(",", $phaseId);
            $PhaseStr = array();
            for ($j=0;$j<count($phaseArr);$j++){
                $PhaseSt = $this->scaleLibraryLib->getPhaseNameById($phaseArr[$j]);
                $PhaseStr[] = $PhaseSt[0]['stu_sec_name'];
            }
            if (count($PhaseStr)>1) {
                $str = implode(",", $PhaseStr);
                $scale[$i]['phaseName'] = $str;
            }


        }

        return $scale;
    }
	
	/**
	 * 评价维度、评价指标联动方法</br>
	 * 开放性:全局开放</br>
	 * 调用方式：/index.php?c=scale_manager_controller&a=showTargetByDim<br/>
	 * 参数说明：无<br/>
	 * @author zzzou
	 * @date 2014-04-28
	 */
	public function showTargetByDim(){
		$dimId = intval($this->spArgs('dim_id'));
		$scaleTarget = $this->scaleLibraryLib->getTargetByDim($dimId);
		echo json_encode($scaleTarget);
	}
	
	/**
	 * 量表库更新方法</br>
	 * 开放性:全局开放</br>
	 * 调用方式：/index.php?c=scale_manager_controller&a=updateScale<br/>
	 * 参数说明：无<br/>
	 * @author zzzou
	 * @date 2014-04-28
	 */
	public function updateScale(){

		//获取前台页面数据
		$scale = $this->spArgs('scale');

		$result = $this->scaleLibraryLib->updateScale($scale);

        echo $result;
	}

    /**
     * 根据条件获取量表
     *
     * 开放性:全局开放</br>
     * 调用方式：/index.php?c=scale_manager_controller&a=getScaleByCondition<br/>
     * 参数说明：无<br/>
     * @author zzzou
     * @date 2014-04-28
     */
    public function getScaleByCondition(){
        $phaseId = intval($this->spArgs('phase_id'));
        $dimensionsId = intval($this->spArgs('dimensions_id'));
        $targetId = intval($this->spArgs('target_id'));
        $typeId = intval($this->spArgs('type_id'));
        $conditions = array();
        if ($typeId && $typeId != 1) {
            $conditions['typeId'] = $typeId;
        }
        if ($dimensionsId && $dimensionsId !=1) {
            $conditions['dimensionsId'] = $dimensionsId;
        }
        if ($phaseId && $phaseId !=1) {
            $conditions['phaseId'] = $phaseId;
        }
        if ($targetId) {
            $conditions['targetId'] = $targetId;
        }
        $scalePhase = $this->scaleLibraryLib->getAllPhase();
        $scaleDim = $this->scaleLibraryLib->getAllDim();
        $scaleTarget = $this->scaleLibraryLib->getTargetByDim(intval($this->spArgs('dimensions_id', 0)));
        $scaleType = $this->scaleLibraryLib->getAllType();

        if($phaseId == ""){
            $phaseId = 1;
        }
        if($dimensionsId == ""){
            $dimensionsId = 1;
        }
        if($typeId == ""){
            $typeId = 1;
        }

        $result = $this->scaleLibraryLib->findByCondition(intval($this->spArgs('page', 1)),$conditions);
        $result['data'] = $this->getPhaseName($result['data']);
        $this->scalePhase = $scalePhase;
        $this->scaleDim = $scaleDim;
        $this->scaleTarget = $scaleTarget;
        $this->scaleType = $scaleType;
        $this->pager = $result;
        $this->data = $result['data'];
        $this->phase_id = $phaseId;
        $this->dimensions_id = $dimensionsId;
        $this->target_id = $targetId;
        $this->type_id = $typeId;
        $this->modelID = 218;
        $this->controller = "scaleManager";
        $this->action = "getScaleByCondition";
        $this->render("scaleLibraryManager/index");
    }


    /**
	 * 量表库添加页面方法</br>
	 * 开放性:全局开放</br>
	 * 调用方式：/index.php?c=scale_manager_controller&a=addScaleIndex<br/>
	 * 参数说明：无<br/>
	 * @author zzzou
	 * @date 2014-04-28
	 */
	public function addScaleIndex(){
		$this->modelID = 218;
		$scaleDim = $this->scaleLibraryLib->getDimsions();
		$scaleTarget = $this->scaleLibraryLib->getTargetByDim($scaleDim[0]['dim_id']);
		$studySec = $this->scaleLibraryLib->getStuSec();
		//把查询出的数据转换成smarty模板需要的数据
		$scaleDims=array();
		foreach ($scaleDim as $key=>$value) {
			$scaleDims[$value['dim_id']]=$value['dim_name'];
		}
		
		$scaleTar = array();
		foreach ($scaleTarget as $key=>$value){
			$scaleTar[$value['target_id']] = $value['target_name'];
		}
		
		$stuSecArr = array();
		foreach ($studySec as $key=>$value){
			$stuSecArr[$value['stu_sec_id']] = $value['stu_sec_name'];
		}
		
		$this->scaleDim=$scaleDims;
		$this->scaleTarget = $scaleTar;
		$this->stuSec = $stuSecArr;
		
		$this->render("scaleLibraryManager/add_scale");
	}
	
	/**
	 * 量表库添加方法</br>
	 * 开放性:全局开放</br>
	 * 调用方式：/index.php?c=scale_manager_controller&a=addScale<br/>
	 * 参数说明：无<br/>
	 * @author zzzou
	 * @date 2014-04-28
	 */
	public function addScale(){
		//获取前台页面的参数
		$scale = $this->spArgs('scale');
		$scale['test_num'] = "NO.".rand(1000, 9999);
        $scale['use_num'] = 10;
		$scale['input_time'] = date('Y-m-d H:i:s');

		$result = $this->scaleLibraryLib->addScal($scale);

        echo($result);
	}

    /**
     * 添加成功后的页面跳转
     *
     * 开放性:全局开放</br>
     * 调用方式：/index.php?c=scaleManager&a=addSuccess<br/>
     * 参数说明：无<br/>
     * @author zzzou
     * @date 2014-05-07
     */
    public function addSuccess(){
        $scaleId = intval($this->spArgs('scale_id'));

        $scale = $this->scaleLibraryLib->getScaleByid($scaleId);
        $this->modelID = 218;
        $this->scale_name=$scale[0]['testName'];
        $this->scale_num = $scale[0]['testNum'];
        $this->render("scaleLibraryManager/add_success");
    }

	/**
	 * 量表库删除方法</br>
	 * 开放性:全局开放</br>
	 * 调用方式：/index.php?c=scale_manager_controller&a=deleteScale<br/>
	 * 参数说明：无<br/>
	 * @author zzzou
	 * @date 2014-04-28
	 */
	public function deleteScale(){
		$scaleId = $this->spArgs('scale_id');
        $this->scaleLibraryLib->deleteScale($scaleId);
        header("Location: index.php?c=scaleManager&a=getScaleByCondition");
	}
	
}