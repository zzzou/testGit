<?php
/**
 * 卷库控制器
 *
 * @package controller
 * @link /controller/QuestionExamController.php
 * @author lpsong
 * @date 2014-5-12
 *
 * @history：
 *
 */
class QuestionExamController extends BaseController{
    /**
     * 参考卷库页面：<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=questionExam&a=index<br/>
     * 参数说明：无<br/>
     */
    public function index(){
        $this->modelID = 22;
        $ps = $this->getExam();
        $this->papers = $ps['data'];

        $class = $this->bankLib->findAllByBankType(3);
        $this->class = $class;
        $this->title = $class['0']['title'];

        $this->pages = array('count'=>$ps['count'],'pageCount'=>$ps['pageCount']);
        $this->render();
    }

    /**
     * 参考卷库页面： 取数据<br/>
     * 开放性：保护<br/>
     * @param int $pageIndex 页码
     * @param int $pageSize 每页显示的数量
     * @param int $bankId 学科id
     * @param int $dateFlag 1(今天)，2(昨天)，3(本周)，4(本月)
     * @param string $schoolId
     */

    protected function getExam($pageIndex='1',$pageSize='10',$bankId='10',$dateFlag=null,$schoolId='4wg3ajgiyrnjfhbx2jcqrq'){
        $preview = $this->questionExamPaperLib->getSchoolExam($pageIndex,$pageSize,$bankId,(int)$dateFlag,$schoolId);
        foreach($preview['data'] as $k=>$v){
            switch($v['GradeId']){
                case 0:
                    $preview['data'][$k]['GradeId'] = '高一';
                    break;
                case 1:
                    $preview['data'][$k]['GradeId'] = '高二';
                    break;
                case 2:
                    $preview['data'][$k]['GradeId'] = '高三';
                    break;
            }
        }
        return $preview;
    }

    /**
     * 参考卷库页面：按今天，昨天，本周，本月来查询数据<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=questionExam&a=paperStory<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function paperStory(){
        $data = $this->getExam($this->spArgs('current'),'10',$this->spArgs('bankId'),$this->spArgs('dateFlag'));
        $this->renderJSON(array($data));
    }


    /**
     * 我的卷库的页面<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=QuestionExam&a=my<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function my(){
        $this->modelID = 20;
        $ps = $this->getMyExam();
        $this->papers = $ps['data'];
        $class = $this->bankLib->findAllByBankType(3);
        $this->class = $class;
        $this->title = $class['0']['title'];

        $this->pages = array('count'=>$ps['count'],'pageCount'=>$ps['pageCount']);
        $this->render();
    }

    /**
     * 我的卷库页面： 取数据<br/>
     * 开放性：保护<br/>
     * @param int $pageIndex 页码
     * @param int $pageSize 每页显示的数量
     * @param int $bankId 学科id
     * @param int $dateFlag 1(今天)，2(昨天)，3(本周,按周一)，4(本月)
     * @param int $examStatus 0（未考）
     * @param string $userId
     */
    protected function getMyExam($pageIndex='1',$pageSize='10',$bankId='10',$dateFlag=null,$examStatus='0',$userId='7oogamcif4rb5xjyuleafg'){
        $preview = $this->questionExamPaperLib->getPersonExam($pageIndex,$pageSize,$bankId,(int)$dateFlag,$examStatus,$userId);
        foreach($preview['data'] as $k=>$v){
            switch($v['GradeId']){
                case 0:
                    $preview['data'][$k]['GradeId'] = '高一';
                    break;
                case 1:
                    $preview['data'][$k]['GradeId'] = '高二';
                    break;
                case 2:
                    $preview['data'][$k]['GradeId'] = '高三';
                    break;
            }
            switch($v['ExamStatus']){
                case 0:
                    $preview['data'][$k]['ExamStatus'] = '未考';
                    break;
                case 2:
                    $preview['data'][$k]['ExamStatus'] = '已考';
                    break;
            }
        }
        return $preview;
    }


    /**
     * 参考卷库页面：按今天，昨天，本周，本月来查询数据<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=questionExam&a=myPaperStory<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function myPaperStory(){
        $current = $this->spArgs('current');
        $dataFlag = $this->spArgs('dataFlag');
        $status = $this->spArgs('status');
        $bankId = $this->spArgs('bankId');
        $dataFlag == '' ? $dataFlag = null : '';
        $status == '' ? $status = 0 : '';
        switch($status){
            case 5:
                $status = 0;
                break;
            case 6:
                $status = 0;
                break;
            case 7:
                $status = 2;
                break;
            default:
                break;
        }
        $data = $this->getMyExam($current,$pageSize='10',$bankId,$dataFlag,$status);
        $this->renderJSON(array($data));
    }

    /**
     * 删除试卷（包括试卷对应的题目和试题目录）
     * @param int  $examId 考试id
     * @return boolean
     */
    public function deleteExam(){
        $bool = $this->questionExamPaperLib->removeExam($this->spArgs('id'));
        $result = $this->buildResult(0, '', $bool);
        $this->renderJSON($result);
    }

    /**
     * 将个人试卷共享或者取消共享到学校组卷
     * @param int  $examId 考试id
     * @return boolean
     */
    public function shareExam(){
        $id = $this->spArgs('id');
        $flag = $this->spArgs('flag');
        if($flag == '0'){
           $code = $this->questionExamPaperLib->removeShareExam($id);
            $msg = '取消共享';
        }else{
           $code = $this->questionExamPaperLib->addShareExam($id);
            $msg = '成功共享';
        }
        $this->renderJSON(array('code'=>$code,'msg'=>$msg));
    }

    /**
     * 卷库的试卷题目变换<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=questionExam&a=changeSubject<br/>
     * 参数说明：无<br/>
     * 提交方式：get<br/>
     */
    public function changeSubject(){
        $id = $this->spArgs('bankId');
        $title = $this->bankLib->findById($id);
        $title = $title['title'];
        //  通过$id 查询  ，科目
        if($this->spArgs('myPaper') == 1){
            $papers = $this->getMyExam(1,10,$id);
        }else{
            $papers = $this->getExam(1,10,$id);
        }
        $this->renderJSON(array($title,$papers));
    }



    /**
     * 题库的翻页代码<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=questionExam&a=changeTable<br/>
     * 传递的参数说明:
     *              $count      要跳转到哪一页的页数
     *              $current    当前在哪一页
     *              $page   每页显示题目数
     *              $total  题目总数
     *              $max    总页数
     *
     * 返回的参数说明：
     *              $html   返回的题目内容
     * 提交方式：post<br/>
     */

    public function changeTable(){
        $count = $this->spArgs('count');
        $current = $this->spArgs('current');
        $time = $this->spArgs('time');
        $status = $this->spArgs('status');
        if($time == 'undefined'){
            $time = null;
        }
        if($count == '-1'){
            $current--;
        }else if($count == '-2'){
            $current++;
        }else{
            $current=$count;
        }
        if($status == 'null'){
            $html = $this->getExam($current,$pageSize='10',$bankId='10',$time);
            $html = $html['data'];
        }else{
            $status == 'undefined' ? $status = 0 : '';
            $html = $this->getMyExam($current,$pageSize='10',$bankId='10',$time,$status);
            $html = $html['data'];
        }
        $this->renderJSON(array($html));
    }

}