<?php
require_once(UTIL_PATH . '/TopicUtil.php');
require_once(UTIL_PATH . '/WebUtil.php');
/**
 * 命题老师任务控制器
 *
 * @package controller
* @link /controller/PropositionTeacherItemController.php
    * @author lpsong
*/
class PropositionTeacherItemController extends BaseController{

    /**
     * 已命命题<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=aleadyTsk<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function aleadyTsk()
    {
        $this->modelID = 187;

        $this->id = $this->spArgs('id');
        $this->propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);
        $proposition = $this->propositionLib->findByPk($this->propositionTeacher['proposition_id']);

        $proposition['textbooks']=json_decode($proposition['textbooks']);
        $proposition['knowledge_points']=json_decode($proposition['knowledge_points']);

        $this->proposition = $proposition;

        $this->topics = $this->propositionItemTopicLib->findAllByPropositionTeacherItemId($this->id);
        $this->render('propositionTeacherItem/alreadyTask');
    }

    /**
     * 从题库添加任务<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=addItem<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function addItem()
    {
        $this->modelID = 187;

        $this->id = $this->spArgs('id');
        $propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);
        $this->propositionTeacher = $propositionTeacher;
        $proposition = $this->propositionLib->findByPk($this->propositionTeacher['proposition_id']);

        $proposition['textbooks']=json_decode($proposition['textbooks']);
        $proposition['knowledge_points']=json_decode($proposition['knowledge_points']);

        $this->proposition = $proposition;

        $this->render('propositionTeacherItem/addItem');
    }

    /**
     * 教师提交命题<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=submitProposition<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function submitProposition()
    {
        $this->modelID = 187;

        $this->id = $this->spArgs('id');
        $propositionTeacher = $this->propositionTeacherItemLib->findByPk($this->id);


        if($propositionTeacher['count'] ==$propositionTeacher['assigned_count'])
        {
            $this->propositionTeacherItemLib->updateState($this->id,1);
            //获取状态为0的个数
            $count = $this->propositionTeacherItemLib->findCountByPropositionIdAndState($propositionTeacher['proposition_id'],0);

            if($count==0)
            {
                //更新命题完成
                $this->propositionLib->updatePropositionState($propositionTeacher['proposition_id'],3);
            }
        }

        header("Location: index.php?c=proposition&a=myTask");
    }

    /**
     * 审核+不通过理由<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=reviewTask<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function reviewTask()
    {
        $this->modelID = 187;

        $id = $this->spArgs('id');
        $this->propositionTeaItem =  $this->propositionTeacherItemLib->findByPk($id);
        $this->proposition =  $this->propositionLib->findByPk($this->propositionTeaItem['proposition_id']);
        $this->id=$id;
        $this->render('propositionTeacherItem/audit');
    }

    /**
     * 教室命题通过审核，更新状态<br/>
     * 状态1为已通过，0为未通过
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=propositionTeacherItem&a=updateTask<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function updateTask()
    {
        $this->modelID = 187;
        $id = $this->spArgs('id');
        // 查询状态为0的是否存在
        $this->propositionTeacherItemLib->updateState($id, '2');
        $item = $this->propositionTeacherItemLib->findByPk($id);
        $result = $this->propositionTeacherItemLib->findCountByPropositionIdAndState($item['proposition_id'], '1');
        if(!$result){
            $audtime=date('Y-m-d H:i:s',time());
            $proposition = $this->propositionLib->updatePropositionStateTimeById($item['proposition_id'],'4',$audtime);
            /**********新增组卷功能开始    添加人：qifang************/
            //根据proposition_id查出PropositionTeacherItemLib表所有信息
            $topicMessage=$this->propositionTeacherItemLib->findAllByPropositionId($item['proposition_id']);
            
            //查出所有题目相关信息
            foreach ($topicMessage as $key=>$value) {
                $topic[]=$this->propositionItemTopicLib->findAllByPropositionTeacherItemId($value['id']);
            }
            $proposition=$this->propositionLib->findByPk($item['proposition_id']);
            $subject=10;//学科 （目前用假数据）
            $schoolId="4wg3ajgiyrnjfhbx2jcqrq";
            $userId="7oogamcif4rb5xjyuleafg";
            $title=$proposition['name'];
            
            $array=array();
            $cate=array();
            //新建一份试卷
            //新建一份试卷所需的参数
            $newExam=array(
            	"title"=>$title,
            	"bankId"=>$subject,
            	"schoolId"=>$schoolId,
            	"userId"=>$userId
            );
            $examId=$this->questionExamPaperLib->add($newExam);
            //创建试卷结构
            
            //各题型数组
            foreach ($topic as $key=>$value) {
            	$cate[$key]['cateName']=$value[0]['topic_type'];
            	$cate[$key]['cateId']=GetTypeIdByName($value[0]['topic_type']);
            }
            $this->questionExamCategoryLib->addExamCategory($examId,$subject,$title,$cate);
            
            //将所有题目加入试卷
            //遍历所有题目
            foreach ($topic as $key=>$value) {
                for ($i = 0; $i <$num=count($value); $i++) {
                    $array['body']=$value[$i]['topic_content'];
                    $array['parse']=$value[$i]['topic_comment'];
                    $array['answer']=$value[$i]['topic_answer'];
                    //$array['content'][$key]['knowledgelds']=$value['topic_knowledge_points'];
                    $array['categoryId']=GetTypeIdByName($value[$i]['topic_type']);
                    $array['dateCreated']=$value[$i]['create_time'];
                    $array['calibStatus']=1;
                    $array['paperTitle']=$proposition['name'];
                    $array['bankId']=$subject;
                    $array['schoolId']=$schoolId;
                    $array['userId']=$userId;
                    $array['examId']=$examId;
                    $this->questionExamQuestionLib->addExamQuestion($array);
                }
            }
           /**********新增组卷功能结束    添加人：qifang************/
        }
        header("Location: index.php?c=proposition&a=myTask");
    }

}