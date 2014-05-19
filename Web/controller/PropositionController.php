<?php
/**
 * 命题控制器
 *
 * @package controller
 * @link /controller/PropositionController.php
 * @author lpsong
 */
class PropositionController extends BaseController{

	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct ();
		$this->modelID = 19;
	}
	
	/**
	 * 我的任务默认页面<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=proposition&a=myTask<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function myTask(){
		$this->modelID = 187;
		$state=$this->spArgs('state');
		$id=$this->spArgs('id');
	
		$userId = $this->getSessionUserId();
		if(!empty($state)&&!empty($id))
		{
	
			$data=array(
					"id"=>$id,
					"state"=>2
			);
            $this->propositionLib->update(array('id'=>$id), $data);
		}
		$this->propositions = $this->propositionLib->findAllByUserAndType($userId, 1);
		$this->topicPropositions = $this->propositionLib->findTopicTaskByUser($userId);
		$this->auditPropositions = $this->propositionLib->findAuditTaskByUser($userId);
		$this->render();
	}
	
    /**
     * 命题任务<br/>
     *
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=proposition&a=index<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function index(){
        $this->modelID = 186;
        $userId = $this->getSessionUserId();

        $this->publishedPropositions = $this->propositionLib->findAllByUser($userId);
        $this->unpublishedPropositions = $this->propositionLib->findAllByUserAndStatus($userId, 0);
        $this->render();
    }

    /**
     * 新建命题任务<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=proposition&a=create<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function create()
    {
        $id=$this->spArgs('id');
        $this->modelID = 186;

        if(!empty($id)){
            $section=array("小学"=>"小学","初中"=>"初中","高中"=>"高中");
            $object=array("一年级"=>"一年级","二年级"=>"二年级","三年级"=>"三年级");
            $difficulty=array("容易"=>"容易","较易"=>"较易","一般"=>"一般","较难"=>"较难","困难"=>"困难");
            $type=array("期末考试"=>"期末考试","期中考试"=>"期中考试","学期测验"=>"学期测验");
            $proposition=$this->propositionLib->findByPk($id);
            $this->proposition=$proposition;
            $this->difficulty=$difficulty;
            $this->object=$object;
            $this->type=$type;
            $this->section=$section;
        }

        $this->id=$id;

        $this->render();
    }

    /**
     * 保存命题信息<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=proposition&a=save<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function save()
    {
        //接受前台参数
        $name=$_REQUEST["name"];
        $study_section=$_REQUEST['study_section'];
        $subject=$_REQUEST['subject'];
        $object=$_REQUEST['object'];
        $type=$_REQUEST['type'];
        $Vote1=$_REQUEST['Vote1'];
        $exam_time=$_REQUEST['exam_time'];
        $start_time=$_REQUEST['start_time'];
        $end_time=$_REQUEST['end_time'];
        $checkid=$_REQUEST['checktype'];
        $organzise=$_REQUEST['organzise'];
        $organziseName=$_REQUEST['organziseName'];

        $test_value=$_REQUEST['test_value'];
        $teacher=$_REQUEST['teacher'];
        $creatTime=date('Y-m-d',time());
        $state=intval($_REQUEST['state']);
        $assign_teacher=$_REQUEST['assign_teacher'];
        $auditor=$_REQUEST['auditor'];
        $saveState= $_REQUEST['saveState'];
        $id=$this->spArgs('id');

        //默认考试时长
        if(empty($exam_time)){
            $exam_time=0;
        }
        //插入数据库的数组
        $data = array(
            "name"=>$name,
            "subject"=>$subject,
            "study_section"=>$study_section,
            "object"=>$object,
            "type"=>$type,
            "difficulty"=>$Vote1,
            "exam_time"=>$exam_time,
            "organize_start_time"=>$start_time,
            "organize_end_time"=>$end_time,
            "create_time"=>$creatTime,
            "score"=>$test_value,
            'user_id'=>$this->getSessionUserId(),
            'username'=>$_SESSION['userInfo']['pj_name']
        );

        //判断状态0是保存1是发送
        $data['state'] = $state==0?0:1;

        if(!empty($organzise)){
            $data['organizers'] = $organziseName;
        }
        if(!empty($teacher)){
            $data['assign_teachers'] = implode(',', $teacher['name']);
        }
        if(!empty($checkid)){
            $data['auditors'] = implode(',', $checkid['name']);
        }
        //id>0 更新proposition表 否则创建新命题
        if($id){
            $data['id'] = $id;
            $this->propositionLib->update(array('id'=>$id), $data);
        }else{
            $id = $this->propositionLib
                ->create($data);
        }
        //先清空proposition_user表在创建
        if(!empty($organzise)){
            if($id){
                $this->propositionUserLib->deleteByPropositionIdAndType($id, 1);
            }

            $array=array(
                "user_id"=>$organzise,
                "proposition_id"=>$id,
                "type"=>1,
                "username"=>$organziseName,
            );
            $this->propositionUserLib->create($array);
        }

        if(!empty($checkid)){
            if($id){
                $this->propositionUserLib->deleteByPropositionIdAndType($id, 3);
            }

            for ($i=0;$i<count($checkid[id]);$i++){
                $array=array(
                    "user_id"=>$checkid[id][$i],
                    "proposition_id"=>$id,
                    "type"=>3,
                    "username"=>$checkid[name][$i]

                );
                $this->propositionUserLib->create($array);
            }
        }

        if(!empty($teacher)){
            if($id){
                $this->propositionUserLib->deleteByPropositionIdAndType($id, 2);
            }

            for ($i=0;$i<count($teacher[id]);$i++){
                $array=array(
                    "user_id"=>$teacher[id][$i],
                    "proposition_id"=>$id,
                    "type"=>2,
                    "username"=>$teacher[name][$i]

                );
                $this->propositionUserLib->create($array);
            }
        }
        //如果状态===OK 表示是先保存的  则返回一个保存创建的ID
        if($saveState=="ok"){
            echo json_encode(array('id'=>$id));
            exit;
        }

        //判断跳转页面
        if($state!=0){
            header("Location: index.php?c=proposition&a=index");
        }else{
            header("Location: index.php?c=proposition&a=create");
        }
    }

    /**
     * 删除未处理的命题任务
     */
    public function deleteTask(){
        $id=$this->spArgs('id');
        if($id>0){
            $result=$this->propositionLib->deleteAllByType($id);
            $this->propositionUserLib->deleteByPk($id);
        }

        $this->redirect(array('a'=>'index'));
    }
    
    /**
     * 任务处理<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=proposition&a=taskProcessing<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function taskProcessing()
    {
    	$this->modelID = 187;
    
    	$id = $this->spArgs('id');
    	$items=$this->propositionItemLib->findAllByPropositionId($id);
    	$proposition= $this->propositionLib->findByPk($id);
    
    	$this->textbooks=json_decode($proposition['textbooks']);
    	$this->knowledge=json_decode($proposition['knowledge_points']);
    	$this->proposition=$proposition;
    	$this->items=$items;
    
    	$this->render();
    }
    /**
     * 任务处理<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=proposition&a=taskAssignment<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function taskAssignment()
    {
    	$topicType="";
    	$username="";
    	$count=0;
    	$this->modelID = 187;
    	$id=$this->spArgs('id');
    	if(!empty($id)){
    		$preview=$this->propositionLib->findByPk($id);
    		$previewItem=$this->propositionItemLib->findAllByPropositionId($id);
    		$previewTeacher=$this->propositionTeacherItemLib->findAllById($id);
    		//JSON转ARRAY
    		$preview['textbooks']=json_decode($preview['textbooks']);
    		$preview['knowledge_points']=json_decode($preview['knowledge_points']);
    
    		//题型名字拼接
    		for ($i=0;$i<count($previewItem);$i++)
    		{
    		$count=$count+$previewItem[$i]['count'];
    		if($i>0)
    		{
    		$topicType.='、'.$previewItem[$i]['topic_type'];
				}else
    		{
    		$topicType.=$previewItem[$i]['topic_type'];
    		}
    
    
    		}
    		//教师名字拼接
    		for ($i=0;$i<count($previewTeacher);$i++)
    		{
    		if($i>0)
    		{
    		$username.='、'.$previewTeacher[$i]['username'];
    		}else{
    		$username.=$previewTeacher[$i]['username'];
    		}
    		$previewTeacher[$i]['total_count']=
    				intval($previewTeacher[$i]['count']/$previewTeacher[$i]['total_count']*100);
    		}
    			
    		//输出前台
    		$this->id = $id;
    		$this->previewItem=$previewItem;
    				$this->textbooks=$preview['textbooks'];
    				$this->knowledge=$preview['knowledge_points'];
			$this->preview=$preview;
    			$this->username=$username;
    			$this->count=$count;
    			$this->topicType=$topicType;
    			$this->previewTeacher=$previewTeacher;
    	}
    
    	$this->render();
	}
	/**
	 * 保存我的任务信息<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=proposition&a=saveMyTask<br/>
	 * 参数说明：无<br/>
	 * 提交方式：post<br/>
	 */
	public function saveMyTask()
	{
		$topic_time=$_REQUEST['topic_time'];
		$checkid=$_REQUEST['checktype'];
		$teacher=$_REQUEST['teacher'];
		$proposition_type=$_REQUEST['proposition_type'];
		$proposition_id=$_REQUEST['proposition_id'];
	
		if(!empty($checkid)){
			$this->propositionUserLib->deleteByPropositionIdAndType($proposition_id, 3);
			for ($i=0;$i<count($checkid[id]);$i++){
				$array=array(
						"user_id"=>$checkid[id][$i],
						"proposition_id"=>$proposition_id,
						"type"=>3,
						"username"=>$checkid[name][$i]
				);
				$this->propositionUserLib->create($array);
			}
		}
		if(!empty($teacher)){
			$this->propositionUserLib->deleteByPropositionIdAndType($proposition_id, 2);
			for ($i=0;$i<count($teacher[id]);$i++){
				$array=array(
						"user_id"=>$teacher[id][$i],
						"proposition_id"=>$proposition_id,
						"type"=>2,
						"username"=>$teacher[name][$i]
				);
				$this->propositionUserLib->create($array);
			}
		}
		if(!empty($proposition_type)){
			$this->propositionItemLib->deleteByPropositionId($proposition_id);
	
			for ($i=0;$i<count($proposition_type[name]);$i++){
	
				$array=array(
						"topic_type"=>$proposition_type[name][$i],
						"proposition_id"=>$proposition_id,
						"count"=>$proposition_type[count][$i]
				);
					
				$this->propositionItemLib->create($array);
			}
		}
	
		$textbooks = $this->spArgs('textbooks');
		$textbooks = json_encode($textbooks);
	
		$knowledgePoints = $this->spArgs('knowledge_points');
		$knowledgePoints = json_encode($knowledgePoints);
	
		$assignTeachers = implode(',', $teacher['name']);
	
	
		$proposition = array(
				'id'=>$proposition_id,
				'textbooks'=>$textbooks,
				'knowledge_points'=>$knowledgePoints,
				'topic_time'=>$topic_time
		);
	
		if(!empty($checkid)){
			$proposition['auditors']  = implode(',', $checkid['name']);
		}
	
		if(!empty($teacher)){
			$proposition['assign_teachers']  = implode(',', $teacher['name']);
		}
	
	
	
		$result=$this->propositionLib->update(array('id'=>$proposition_id), $proposition);
	
		header("Location: index.php?c=proposition&a=taskAssignment&id=$proposition_id");
	}
	
	/**
	 * 保存分配命题信息<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=proposition&a=saveAssignment<br/>
	 * 参数说明：无<br/>
	 * 提交方式：post<br/>
	 */
	public function saveAssignment(){
		$id=$this->spArgs('id');
		$data=$this->spArgs('data');
	
		$data = json_decode($data, true);
		$rows = $data['rows'];
	
		$propositionTeacherItems = array();
		foreach ($rows as $row) {
			$propositionTeacherItems[] = array(
				'user_id'=>$row['teacher'],
				'proposition_id'=>$id,
				'topic_type'=>$row['problemType'],
				'count'=>$row['already'],
				'username'=>$row['teacherName'],
				'knowledge_points'=>$row['knowledge'],
				'total_count'=>$row['count']
			);
		}
	
		$this->PropositionTeacherItemLib->deleteByPropositionId($id);
		$this->propositionTeacherItemLib->createAll($propositionTeacherItems);
	
		header("Location: index.php?c=proposition&a=myTask");
	}

    public function getPropositionKnowledgePoints()
    {
        $this->modelID = 187;

        $id=$this->spArgs('id');

        if(!empty($id)){
            $proposition = $this->propositionLib->findByPk($id);

            $knowledgePoints = $proposition['knowledge_points'];

            $knowledgePoints = json_decode($knowledgePoints, true);

            header('Content-Type:application/json');
            echo json_encode($knowledgePoints);
        }
    }
    
    /**
     * 异步读取命题情况<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=proposition&a=ajaxTaskAssignment<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function ajaxTaskAssignment()
    {
        $this->modelID = 187;

        $id=$this->spArgs('id');

        if(!empty($id)){
            $previewItem=$this->propositionItemLib->findAllByPropositionId($id);
            foreach($previewItem as $k => $v){
                $array[$k] = array('problemType'=>$v['topic_type'],'count'=>$v['count'],'already'=>0,'percent'=>0,'person'=>0);
            }

            $propositionUsers = $this->propositionUserLib->findAllByPropositionAndType($id, 2);

            array_push($array,array('problemType'=>'命题情况','count'=>'<font color="red">未完成</font>'));
            array_push($array,array('problemType'=>'未分配教师','count'=>$propositionUsers));

            header('Content-Type:application/json');
            echo json_encode($array);
        }
    }

    /**
     * 命题进度
     */
    public function process(){
        $this->render();
    }
}