<?php
include_once(UTIL_PATH . '/XmlUtil.php');
/**
 * 心里量表控制器
 *
 * @package controller
 * @link /controller/ScaleController.php
 * @author 宋利鹏 lpsong@iflytek.com
 * @date 2014-3-26 11:36
 *
 * @history：
 *
 */
class ScaleController extends BaseController {

    /**
     * 量表<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=scale&a=getScaleLibrary<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function getScaleLibrary()
    {
        $this->modelID = 189;
        $this->render('scaleLibrary/index');
    }

    /**
     * 页面跳转 方法
     */
    public function dis(){
		$this->render("teacher/question_storage_system/scale_library/index");
	}
	
	/**
	 * 获取考试
	 */
	public function getExam(){
		$phaseId = intval($this->spArgs('phase_id'));
		$dimensionsId = intval($this->spArgs('dimensions_id'));
		$targetId = intval($this->spArgs('target_id'));
		$typeId = intval($this->spArgs('type_id'));
        $conditions = array();
        //根据各接收值，生成条件
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

		$result = $this->scaleLibraryLib->findByCondition(intval($this->spArgs('page', 1)),$conditions);
		$result['data'] = $this->getPhaseName($result['data']);

        //设置默认值
        if($phaseId == ""){
            $phaseId = 1;
        }
        if($dimensionsId == ""){
            $dimensionsId = 1;
        }
        if($typeId == ""){
            $typeId = 1;
        }

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
        $this->modelID = 189;


        $this->controller = "scale";
        $this->action = "getExam";
		$this->render("scaleLibrary/index");
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
	 * 发起评测<br/>
	 * 开放性:全局开放<br/>
	 * 调用方式: /index.php?c=scale&a=getNewAssessment<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getNewAssessment(){
		$this->modelID = 189;
		$classId = intval($this->spArgs('class_id'));
		//获取测评ID
		$testNum=intval($this->spArgs('exam_id'));
		$this->scale_id=$testNum;
		//根据测评ID获取评测内容
		$scaleMsg=$this->scaleLibraryLib->getScaleById($testNum);
		$this->scaleArray=$scaleMsg;
		
		//根据测评内容中学段信息判断测评对象信息
		$phaseName=$scaleMsg[0]['stu_sec_id'];
		$phaseNames=explode(",",$phaseName);
		
		
		$content=file_get_contents('js/json/scaleObj.json');
		$obj=json_decode($content,true);
		$gradeMsg=array();
		
		foreach ($phaseNames as $value1){
			
			foreach ($obj as $value) {
				if ($value['stuId']==$value1) {//找出学段信息对应的对象信息
					$gradeMsg[$value1]=$value['grade'];
					continue;
				}
			}
		}
		$this->grade=$gradeMsg;

		$this->render('scaleLibrary/fqpc');
	}

    /**
     * 保存评测任务<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=scale&a=saveAssessment<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function saveAssessment(){
        //接受前台参数
        $fqcp_cpwd=$_REQUEST['fqcp_cpwd'];
        $fqcp_cpzb=$_REQUEST['fqcp_cpzb'];
        $cpmc=$_REQUEST['pcmc'];
        $start_time=$_REQUEST['start_time'];
        $end_time=$_REQUEST['end_time'];
        $fqcp_cpdx=$_REQUEST['gb1_cpdx'];
        $creatTime=date('Y-m-d',time());
        $state=1;
        $fqcp_lbnum=$_REQUEST['fqcp_lbnum'];

        //插入数据库数组

        $data=array(
            "name"=>$cpmc,
            "scale_id"=>$fqcp_lbnum,
            "assessment_obj"=>$fqcp_cpdx,
            "assessment_wd"=>$fqcp_cpwd,
            "assessment_zb"=>$fqcp_cpzb,
            "organize_start_time"=>$start_time,
            "organize_end_time"=>$end_time,
            "state_time"=>$creatTime,
            "state"=>$state,
        );


        $this->assessmentTaskLib->create($data);


        header("Location: index.php?c=scale&a=getAssessmentTasks");

    }

    /**
     * 测评任务<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=scale&a=getScaleLibrary<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function getAssessmentTasks()
    {
        $this->modelID = 190;
        $userId=1;
        $this->goNum=0;
        $this->finishNum=0;
        $assementTasks=$this->assessmentTaskLib->findAllByUser($userId);
        $this->publicAssementTasks=$assementTasks;
        $this->render('scaleLibrary/assessmentTasksIndex');
    }
}