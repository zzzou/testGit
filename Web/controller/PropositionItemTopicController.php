<?php
/**
 * 命题题目控制器
 *
 * @package controller
 * @link /controller/PropositionItemTopicController.php
 * @author lpsong
 */
class PropositionItemTopicController extends BaseController{

    /**
     * 新建试题<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionItemTopic&a=newTopic<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function create(){
        $this->modelID = 187;

        $this->id = $this->spArgs('id');
        $propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);
        $this->propositionTeacher = $propositionTeacher;
        $proposition = $this->propositionLib->findByPk($this->propositionTeacher['proposition_id']);

        $proposition['textbooks']=json_decode($proposition['textbooks']);
        $proposition['knowledge_points']=json_decode($proposition['knowledge_points']);

        $this->proposition = $proposition;

        $this->render('propositionItemTopic/create');
    }

    /**
     * 保存题目<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=update<br/>
     * 参数说明：无<br/>
     * 提交方式：post<br/>
     */
    public function save(){
        $this->id = $this->spArgs('id');
        $propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);
        $this->proposition = $this->propositionLib->findByPk($propositionTeacher['proposition_id']);

        $propositionItemId = $this->propositionItemLib->findAllByPropositionIdAndTopicType($propositionTeacher['proposition_id'], $propositionTeacher['topic_type']);

        $content = $this->spArgs("editorContent");
        $answer = $this->spArgs("editorAnswer");
        $comment = $this->spArgs("editorComment");
        $difficulty =$this->spArgs("difficulty");
        $knowledgePoints=$this->spArgs("knowledge");
        $cognitivelevel=$this->spArgs("cognitivelevel");
        $video = $this->spArgs("video");
        //插入数据库的数组
        $data = array(
            "topic_content"=>$content,
            "topic_answer"=>$answer,
            "topic_comment"=>$comment,
            "topic_difficulty"=>$difficulty,
            "topic_knowledge_points"=> $knowledgePoints,
            "topic_cognitive_level"=>$cognitivelevel,
            "topic_type"=>$propositionTeacher['topic_type'],
            "proposition_teacher_item_id"=>$propositionTeacher['id'],
            "proposition_item_id"=>$propositionItemId[0]['id'],
            "create_time"=>date('Y-m-d',time()),
            "video" => $video
        );

        $this->propositionItemTopicLib->create($data);

        $this->propositionItemTopicLib->updateAssignedCount($this->id);
        
        //保存到我的题库        
        $quesiton=array(
        	//'userId'=>$propositionTeacher['id'],
        	'userId'=>"7oogamcif4rb5xjyuleafg",
			'schoolId'=>"4wg3ajgiyrnjfhbx2jcqrq",
        	'body'=>$content,
        	'answer'=>$answer,
        	'parse'=>$comment
        );
        $this->questionLib->addNewQuestion($quesiton);

        $this->redirect(array('c'=>'propositionTeacherItem', 'a'=>'aleadyTsk', 'id'=>$this->id));
    }

    /**
     * 编辑题目<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=editorTopic<br/>
     * 参数说明：无<br/>
     * 提交方式：post<br/>
     */
    public function edit(){
        $this->modelID = 187;

        $this->id = $this->spArgs('id');
        $this->topicId = $this->spArgs('topicId');
        $propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);
        $this->propositionTeacher = $propositionTeacher;
        $proposition = $this->propositionLib->findByPk($this->propositionTeacher['proposition_id']);

        $proposition['textbooks']=json_decode($proposition['textbooks']);
        $proposition['knowledge_points']=json_decode($proposition['knowledge_points']);
        $this->proposition = $proposition;

        $this->topicId=$this->spArgs('topicId');

        $this->propositionTopic=$this->propositionItemTopicLib->findByPk($this->spArgs('topicId'));

        $this->render('propositionItemTopic/edit');
    }

    /**
     * 保存修改<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=saveChangeTopic<br/>
     * 参数说明：无<br/>
     * 提交方式：post<br/>
     */
    public function update(){
        $this->id = $this->spArgs('id');
        $propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);
        $this->proposition = $this->propositionLib->findByPk($propositionTeacher['proposition_id']);

        $propositionItemId = $this->propositionItemLib->findAllByPropositionIdAndTopicType($propositionTeacher['proposition_id'], $propositionTeacher['topic_type']);

        $content = $this->spArgs("editorContent");
        $answer = $this->spArgs("editorAnswer");
        $comment = $this->spArgs("editorComment");
        $difficulty =$this->spArgs("difficulty");
        $knowledgePoints=$this->spArgs("knowledge");
        $cognitivelevel=$this->spArgs("cognitivelevel");
        $video = $this->spArgs("video");
        //更新数据库的数组
        $data = array(
            "id"=>$this->spArgs('topicId'),
            "topic_content"=>$content,
            "topic_answer"=>$answer,
            "topic_comment"=>$comment,
            "topic_difficulty"=>$difficulty,
            "topic_knowledge_points"=> $knowledgePoints,
            "topic_cognitive_level"=>$cognitivelevel,
            "topic_type"=>$propositionTeacher['topic_type'],
            "proposition_teacher_item_id"=>$propositionTeacher['id'],
            "proposition_item_id"=>$propositionItemId[0]['id'],
            "video"=>$video
        );


        $this->propositionItemTopicLib->updateTopic($data);

        $this->redirect(array('c'=>'propositionTeacherItem', 'a'=>'aleadyTsk', 'id'=>$this->id));
    }

    /**
     * 删除命题题目
     */
    public function delete(){
        $id = $this->spArgs('id');
        // 删除前验证是否是自己创建的 
        $this->propositionItemTopicLib->deleteByPk($id);

        $proposition_teacher_item_id = $this->spArgs('proposition_teacher_item_id');

        $this->redirect(array('c'=>'propositionTeacherItem', 'a'=>'aleadyTsk', 'id'=>$proposition_teacher_item_id));
    }

    /**
     * 题目内容<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionItemTopic&a=getProposition<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function getProposition(){
        $temp = $_GET['temp'];
        $result=$this->propositionItemTopicLib->findPropositionTeaItemTopic($temp);
        echo json_encode($result);
    }

    /**
     * 审核题目的状态和不通过理由<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionItemTopic&a=updatePropositionItemTopicById<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function updateProTopicStateReasonById(){
        $topicId = $_GET['topicId'];
        $state=$_GET['state'];
        $reason=$_GET['reason'];
        $result=$this->propositionItemTopicLib->updateProTopicStateReasonById($topicId,$state,$reason);
    }

    /**
     * 获得题目<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionItemTopic&a=getPropositionitemTopicById<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function getPropositionitemTopicById(){
        $topicId = $_GET['topicId'];
        $result=  $this->propositionItemTopicLib->findByPk($topicId);
        echo json_encode($result);
    }

    /**
     * 审核题目的状态<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionItemTopic&a=updatePropositionItemTopicById<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function updatePropositionItemTopicById(){
        $topicId = $_GET['topicId'];
        $state=$_GET['state'];
        $teaId=$_GET['teaId'];
        $audCount=$_GET['audCount'];
        $this->propositionItemTopicLib->updateProTopicStateReasonById($topicId,$state);
        $this->propositionTeacherItemLib->updateAuditedCount($teaId,$audCount);

    }

    /**
     * 删除upload文件夹的文件<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=teacher_controller&a=deleteFile&url=$url<br/>
     * 参数说明：@param  $url  文件的路径
     * 提交方式：post<br/>
     */
    public function deleteFile(){
        $url = $this->spArgs('url');
        $newUrl = $this->spArgs('newUrl');
        //更新数据库的数组
        $data = array(
            "id"=>$this->spArgs('topicId'),
            "video"=>$newUrl
        );
        $libPropositionTopic=$this->propositionItemTopicLib->updateTopic($data);
        return var_dump(unlink(APP_PATH.$url));
    }

    /**
     * 提交已命题题目
     */
    public function submit(){
        $id = $this->spArgs('id');
        if(!$id){
            $this->notFound();
        }

        $topic = $this->propositionItemTopicLib->findByPk($id);
        if(!$topic){
             $this->notFound();
        }

        $teacherItem = $this->propositionTeacherItemLib->findByPk($topic['proposition_teacher_item_id']);
        if($teacherItem['user_id']!=$this->getSessionUserId()){
            $this->notFound();
        }

        $this->propositionItemTopicLib->updateByPk($id, array('is_submit'=>true));

        return $this->renderJSON($this->buildResult());
    }
}