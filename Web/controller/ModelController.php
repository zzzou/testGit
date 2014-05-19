<?php
/**
 * 模块控制器
 *
 * @package controller
 * @link /controller/ModelController.php
 * @author yunyang
 * @date 2014-3-20
 *
 * @history：
 *
 */
class ModelController extends BaseController{
    /**
     * 获取某个顶层菜单<br/>
     * 开放性:对登录用户开放
     * 调用方式：/index.php?c=model_controller&a=getTopModel<br/>
     * 参数说明：无
     * 提交方式：get/post<br/>
     */
    public function getTopModel(){

        if(!isset($_SESSION['role']) || $_SESSION['role'] == null){
            echo $this->buildResult(-1, "请先登录", null);
            return;
        }
        echo $this->buildResult(1, "查询数据成功", spClass('ModelLib')->getModels($_SESSION['role'], 3));
    }

	/**
	 * 获取个角色顶层菜单<br/>
	 * 开放性:对登录用户开放
	 * 调用方式：/index.php?c=model_controller&a=getTopModels<br/>
	 * 参数说明：无
	 * 提交方式：get/post<br/>
	 */
	public function getTopModels(){
		//TODO：
		if(!isset($_SESSION['role']) || $_SESSION['role'] == null){
			echo $this->buildResult(-1, "请先登录", null);
			return;
		}
		echo $this->buildResult(1, "查询数据成功", model::getTopModels($_SESSION['role']));
		
		
	}
	
	/**
	 * 获取带有子菜单的子菜单列表<br/>
	 * 开放性:对登录用户开放
	 * 调用方式：/index.php?c=model_controller&a=getSubModelsWithChilds&modelId=yourModelId<br/>
	 * 参数说明：modelId 模块ID
	 * 提交方式：get/post<br/>
	 */
	public function getSubModelsWithChilds(){
		$modelId = $this->spArgs('modelId');
		if(!isset($_SESSION['role'])){
			echo $this->buildResult(-1, "请先登录", null);
			return;
		}
		if(!isset($modelId)){
			echo $this->buildResult(-1, "modelId 参数为空", null);
			return;
		}
		echo $this->buildResult(1, "查询数据成功", model::getSubModelsWithChilds($_SESSION['role'],$modelId));
	}
	
	/**
	 * 获取子菜单列表<br/>
	 * 开放性:对登录用户开放
	 * 调用方式：/index.php?c=getSubModels&a=getSubModels&modelId=yourModelId<br/>
	 * 参数说明：modelId 模块ID
	 * 提交方式：get/post<br/>
	 */
	public function getSubModels(){
		$modelId = $this->spArgs('modelId');
		if(!isset($_SESSION['role'])){
			echo $this->buildResult(-1, "请先登录", null);
			return;
		}
		if(!isset($modelId)){
			echo $this->buildResult(-1, "modelId 参数为空", null);
			return;
		}
		echo $this->buildResult(1, "查询数据成功", model::getSubModels($_SESSION['role']),$modelId);
	}
	
	
}

?>