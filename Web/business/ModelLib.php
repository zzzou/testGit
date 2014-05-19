<?php
/**
 * 获取用户对应的模块 ：用于生成导航
 * 
 * @package lib
 * @category lib
 * @link /lib/ModelLib.php
 * @author guanhan
 * @time 2014-3-20
 */
class ModelLib extends BaseLib {
	public $model;
	
	/**
	 * 数据数组
	 */
	private  $datas = array();
	
	/**
	 * 构造函数
	 */	
	public function __construct() {
		$this->model = spClass ( "ModelModel" );
		$this->datas = $this->getUserModelList($_SESSION['role']);
	}
	
    /**
     * 根据指定的模块id获取模块信息
     * @param  int $modelID 指定的模块id
     * @return array 模块信息
     * @author xiaolei
     */
    public function getModel($modelID) {
        foreach($this->datas as $model) {
            if($model['model_id'] == $modelID) {
                return $model;
            }
        }
        return array();
    }
	
	
    /**
     * 根据指定modelID，获取其所在的顶层modelID下所有的model
     * @param $type string 角色
     * @param $modelID int 指定模块ID
     * @author xiaolei
     */
    public function getModels($type, $modelID){
        $topModel = $this->getTopModel($type, $modelID);
        $models = $this->getSubModelsWithChilds($type, $topModel['model_id']);
        return $models;
    }
	
	
    /**
     * 获取指定模块的顶层模块<br/>
     * @param $type string 角色<br/>
     * @param $modelID int 指定模块ID<br/>
     * @return array 如果找到，则返回找到的模块map，否则返回空map
     * @author xiaolei
     */
    public function getTopModel($type, $modelID) {
        $topModel = null;

        foreach($this->datas as $model) {
            if($model['type'] == $type && $model['model_id'] == $modelID) {
                $topModel = $model;
                break;
            }
        }

        while($topModel['prt_model_id'] != -1){
            $hasSearch = false;
            foreach($this->datas as $model) {
                if($model['type'] == $type && $model['model_id'] == $topModel['prt_model_id']) {
                    $topModel = $model;
                    $hasSearch = true;
                    break;
                }
            }
            if(!$hasSearch && $topModel['prt_model_id'] != -1) {
                $topModel = array();
                break;
            }
        }
        return $topModel;
    }

	/**
	 * 获取用户顶级Model数组
	 * 
	 * @param string $type
	 *        	用户类型
	 * @return array Model数组
	 */
	public  function getTopModels($type) {
		$arr = array ();
		foreach ( $this->datas as $model ) {
			if ($model ['type'] == $type && $model ['model_level'] == 0) {
				array_push ( $arr, $model );
			}
		}
		return $arr;
	}
	
	/**
	 * 获取子Model数组
	 * 
	 * @param string $type
	 *        	用户类型
	 * @param int $parentId
	 *        	父节点Id
	 * @return array Model数组
	 */
	public  function getSubModels($type, $parentId) {
		$arr = array ();
		foreach ( $this->datas as $model ) {
			if ($model ['type'] == $type && $model ['prt_model_id'] == $parentId) {
				array_push ( $arr, $model );
			}
		}
		return $arr;
	}
	
	/**
	 * 获取带子节点的子Model数组
	 * 
	 * @param string $type
	 *        	用户类型
	 * @param int $parentId
	 *        	父节点Id
	 * @return array 带子节点的子Model数组
	 */
	public  function getSubModelsWithChilds($type, $parentId) {
		$arr = array ();
		foreach ( $this->datas as $model ) {
			if ($model ['type'] == $type && $model ['prt_model_id'] == $parentId) {
				if ($model ['model_level'] != 3) {
					$model ["childs"] = $this->getSubModelsWithChilds ( $type, $model ['model_id'] );
				}
                if($model['model_level'] == 0){
                    array_push ( $arr, $model['childs'] );
                } else {
                    array_push ( $arr, $model);
                }

			}
		}
		return $arr;
	}
	
	
	/**
	 * 获取用户类型对用的模块数组
	 * 
	 * @param int $user_type
	 *        	用户类型
	 * @param int $user_id     
	 * 			用户id  
	 * @return array Model数组
	 * @author guanhan
	 */
	public function getUserModelList($user_type){
		$result = array();
		$sql="select a.model_id,a.model_name,a.model_level
				,a.prt_model_id,a.url ,b.user_type as type from model a 
				,userType_model b where a.model_id = b.model_id 
				and b.user_type=".$user_type;
		$result = $this->model->findsql($sql);
		if($result !="" || $result != null){
			$length = count($result);
			for ($i = 0; $i < $length; $i++){
				if($result[$i]['prt_model_id'] == null || $result[$i]['prt_model_id'] ==""  ){
					$result[$i]['prt_model_id'] = -1;
				}
			}
		}
		
		return $result; 
	}
	
	/**
	 *
	 * 根据modelID获取科目ID与科目名
	 *
	 * @param int $modelID
	 * @return array Model数组
	 * @author jianyang
	 */
	public function getSubjectBymodelId($modelID){
		if (empty ( $modelID )) {
			return null;
		}
		$modelID = $this->model->escape($modelID);
		$sql="SELECT
		sjt.sjt_id,
		sjt.sjt_name
		FROM
		model AS md
		INNER JOIN model_report AS mr ON md.model_id = mr.model_id
		INNER JOIN subject_report AS sr ON mr.report_id = sr.report_id
		INNER JOIN `subject` AS sjt ON sjt.sjt_id = sr.sjt_id
		WHERE
		mr.model_id = $modelID";
		return $this->model->findsql($sql);
	
	}

}