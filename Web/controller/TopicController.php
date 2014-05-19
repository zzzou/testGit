<?php
require_once(UTIL_PATH . '/WebUtil.php');
require_once(UTIL_PATH . '/TopicUtil.php');

/**
 * 题库系统接口
 * 
 * @package controller
 * @link /controller/TopicController.php
 * @author lpsong
 */
class TopicController extends BaseController {

    /**
      * 获取科目知识点和科目章节
      */
    public function subjectPoints() {
        $categoryId = $this->spArgs("CategoryId");
        $parentId = $this->spArgs("ParentId");
        $params = array();
        if($parentId){
            $params['ParentId'] = $parentId;
        }
        $url = "knowledge-getTreeNode?CategoryId={$categoryId}";

        header('Content-Type:application/json');
        echo web_ti_api($url, $params);     
    }

    /**
      * 获取科目教材列表
      */
    public function subjectMaterials(){
        $bankId = $this->spArgs("bankId");
        $params = array();
        if($bankId){
            $params['bankId'] = $bankId;
        }
        $params['bankIds'] = '';
        $url = "common-getCategoryByBank";

        header('Content-Type:application/json');
        echo web_ti_api($url, $params);
    }

    /**
      * 获取科目
      */
    public function subjects(){
        $params = array();
        $url = "common-getSubjects";

        header('Content-Type:application/json');
        echo web_ti_api($url, $params);
    }
    
    /**
     * 获取题库中题目 
     * 
     * User：qiancheng
     * 
     * 参数说明
     * pageIndex：页码；
     * pageSize:每页显示的题目数量；
     * bankId:题库Id；
     * yearNo:年度；
     * knowledgeId:知识点Id;
     * categoryId：题型Id;
     * gradeId:年级Id；
     * typeId:类型id；
     * diffId:难度Id
     */
    public function question(){
    	$pageIndex = $this->spArgs("pageIndex");
    	$pageSize = $this->spArgs("pageSize");
    	$bankId = $this->spArgs("bankId");
    	$yearNo = $this->spArgs("yearNo");
    	$knowledgeId = $this->spArgs("knowledgeId");
    	$categoryId = $this->spArgs("categoryId");
    	$gradeId = $this->spArgs("gradeId");
    	$typeId=$this->spArgs("typeId");
    	$diffId=$this->spArgs("diffId");

        $topicTypes = topicGetTopicTypes();
        $temp = array_flip($topicTypes);
        $categoryId = $temp[$categoryId];
    	
    	$params=array();
    	if ($pageIndex) {
    		$params["pageIndex"]=$pageIndex;
    	}
    	if ($pageSize) {
    		$params["pageSize"]=$pageSize;
    	}
    	if ($bankId) {
    		$params["bankId"]=$bankId;
    	}
    	if ($yearNo) {
    		$params["yearNo"]=$yearNo;
    	}
    	if ($knowledgeId) {
    		$params["knowledgeId"]=$knowledgeId;
    	}
    	if ($categoryId) {
    		$params["categoryId"]=$categoryId;
    	}
    	if ($gradeId) {
    		$params["gradeId"]=$gradeId;
    	}
    	if ($typeId) {
    		$params["typeId"]=$typeId;
    	}
    	if ($diffId) {
    		$params["diffId"]=$diffId;
    	}
    	
    	$url ="knowledge-GetQuestionInfo";
    	
    	$json = web_ti_api($url,$params);
        $data = json_decode($json, true);
        $diffculties = topicGetDiffculties();
        foreach ($data['data'] as $key=>$value) {
            $value['DiffId'] = $diffculties[$value['DiffId']];
            preg_match('/\d+/', $value['DateCreated'], $matches);
            $value['DateCreated'] = strftime('%Y-%d-%m', intval($time));
            $value['CategoryId'] = $topicTypes[$value['CategoryId']];
            $data['data'][$key] = $value;
        }

        header('Content-Type:application/json');
        echo json_encode($data);
    }
    
} 