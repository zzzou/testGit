<?php
/**
 * 所有lib的基类，用户前后期需求的特殊处理
 * 
 * @package lib
 * @link /lib/BaseLib.php
 * @author guanhan
 * @date 2014-3-20
 * 
 */
class BaseLib {

	protected $modelName;

	/**
	 * 构造函数
	 */
	public function __construct(){
		if($this->modelName){
			$this->model = spClass($this->modelName);
		}
	}

	/**
	 * 魔术函数，执行模型扩展类的自动加载及使用
	 */
	function __call($method, $args)
	{

		if(isset($this->model)){
			if(method_exists($this->model, $method)){
				$result = call_user_func_array(array($this->model, $method), $args);
				return $result;
			}
		}

		if(preg_match('/(findAllBy|findBy|findCountBy|deleteBy)\w+/', $method, $matches)){
			$result = call_user_func_array(array($this->model, $method), $args);
			return $result;
		}
	}
}