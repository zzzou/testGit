<?php
class BaseModel extends spModel{

	/**
	 * 魔术函数，执行模型扩展类的自动加载及使用
	 */
	function __call($method, $args)
	{
		if(preg_match_all('/findAllBy(\w+(And(\w+))*)/', $method, $matches)){
			$params = $this->covertMethodNameToParams($method, $matches, $args);
			$result = $this->findAll($params);
			return $result;
		}else if(preg_match_all('/findBy(\w+(And(\w+))*)/', $method, $matches)){
			$params = $this->covertMethodNameToParams($method, $matches, $args);
			$result = $this->find($params);
			return $result;
		}else if(preg_match_all('/findCountBy(\w+(And\w+)*)/', $method, $matches)){
			$params = $this->covertMethodNameToParams($method, $matches, $args);
			$result = $this->findCount($params);
			return $result;
		}else if(preg_match_all('/deleteBy(\w+(And\w+)*)/', $method, $matches)){
			$params = $this->covertMethodNameToParams($method, $matches, $args);
			$result = $this->delete($params);
			return $result;
		}elseif(in_array($method, array('select', 'from', 'where', 'group', 'order', 'all', 'first', 'page'))){
			if(!isset($this->query)){
				$this->query = new ModelQuery($this);
			}
			
			$result = call_user_func_array(array($this->query, $method), $args);

			// 查询方法后清空查询条件
			if(in_array($method, array('all', 'first', 'page'))){
				$this->query = new ModelQuery($this);
			}

			return $result;
		}

        return parent::__call($method, $args);
	}

	private function covertMethodNameToParams($methodName, $matches, $args){
		$params = false;
		if($matches[1][0]){
			$str = $matches[1][0];
			$str = explode('And', $str);
			foreach ($str as $key=>$value) {
				$fieldName = $this->covertFieldName($value);
				$params[$fieldName] = $args[$key];
			}
		}
		return $params;
	}

	private function covertFieldName($fieldName){
		$array = preg_split('/(?=[A-Z])/', $fieldName, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($array as $key => $value) {
			$array[$key] = lcfirst($value);
		}
		return implode('_', $array);
	}

	/**
	 * 根据主键查找
	 *
	 * @param int $pk
	 * @return array 
	 */
	public function findByPk($pk){
		return $this->find(array($this->pk=>$pk));
	}

	/**
	 * 创建前调用
	 * 
	 * @return false|array 如果阻止创建，返回false，否则处理数据后返回处理后的数组
	 */
	protected function beforeCreate($row){
		return $row;
	}

	/**
	 * 创建
	 * 
	 * @param array $row
	 * @return false|int 返回主键或false
	 */
	public function create($row){
		$row = $this->beforeCreate($row);

		if(!$row){
			return false;
		}

		$result = parent::create($row);

		if($result){
			$row[$this->pk] = $result;
			$this->afterCreate($row);
		}

		return $result;
	}

	/**
	 * 创建后的回调函数
	 *
	 * @param array $row 插入的数据，包含主键
	 */
	protected function afterCreate($row){
		
	}

	/**
	 * 删除前回调函数
	 *
	 * @param int $id
	 * @return bool 默认返回true，如果要阻止删除返回false
	 */
	protected function beforeDelete($data){
		return true;
	}

	/**
	 * 按照主键删除
	 *
	 * @param int $pk 主键
	 * @return bool 返回删除结果
	 */
	public function deleteByPk($pk){
		$data = $this->findByPk($pk);

		$result = $this->beforeDelete($data);

		if(!$result){
			return false;
		}

		$result = parent::deleteByPk($pk);

		if($result){
			$this->afterDelete($data);
		}

		return $result;
	}

	/**
	 * 删除后回调函数
	 *
	 * @param array $data 删除前的数据
	 */
	protected function afterDelete($data){

	}


	/**
	 * 更新前回调函数
	 *
	 * @param int $pk 主键
	 * @param array $data 数据
	 * @return bool 默认返回数据，如果要阻止删除返回false
	 */
	protected function beforeUpdate($pk, $data){
		return $data;
	}

	public function updateByPk($pk, $data){
		$data = $this->beforeUpdate($pk, $data);

		if(!$data){
			return false;
		}
		$result = $this->update(array($this->pk=>$pk), $data);
		$data = $this->findByPk($pk);

		if($result){
			$this->afterUpdate($data);
		}

		return $result;
	}

	/**
	 * 更新后回调函数
	 * 
	 * @param array $data 数据
	 */
	protected function afterUpdate($data){

	}
}

/**
 * Model查询接口
 *
 * @package model
 * @author lpsong
 */
class ModelQuery {
	private $select;
	private $from;
	private $where;
	private $order;
	private $group;
	private $query;
	private $model;

	/**
	 * 构造函数
	 *
	 * @param BaseModel $model
	 */
	public function ModelQuery($model){
		$this->model = $model;
		$this->select = $model->table . '.*';
		$this->from = $model->table;
	}

	/**
	 * 传递查询字段,重复调用会覆盖
	 *
	 * @param array|string $select 查询字段
	 */
	public function select($select){
		$this->select = $select;

		return $this->model;
	}

	/**
	 * 查询表格,重复调用会覆盖
	 *
	 * @param string $from 查询表
	 */
	public function from($from){
		$this->from = $from;

		return $this->model;
	}

	/**
	 * 查询条件，重复调用会叠加
	 *
	 * @param array|string 查询条件
	 */
	public function where($array){
		$this->where = $array;

		return $this->model;
	}

	/**
	 * group条件
	 *
	 * @param string group条件
	 */
	public function group($group){
		$this->group = $group;

		return $this->model;
	}


	/**
	 * 设置排序,可以多次调用，同时起效
	 *
	 * @param string|array 排序
	 */
	public function order($order){
		if($this->order){
			if(is_string($this->order)){
				$orders[] = $this->order;
				$this->order = $orders;
			}

			$this->order[] = $order;
		}else{
			$this->order = $order;
		}
		

		return $this->model;
	}

	/**
	 * 查询所有数据
	 * 
	 * @return array 所有数据
	 */
	public function all(){
		$this->makeSql();
		return $this->model->findSql($this->query);
	}

	/**
	 * 查询第一条数据
	 *
	 * @return array 第一条数据
	 */
	public function first(){
		$this->makeSql();
		$sql = $this->query;
		$sql .= ' LIMIT 0,1';
		$array = $this->model->findSql($sql);
		return array_pop($array);
	}

	public function page($page, $limit){
		$this->makeSql();
		$data = $this->model->spPager($page, $limit)->findSql($this->query);
		$page = $this->model->spPager()->getPager();
		$page['data'] = $data;

		return $page;
	}

	/**
	 * 生成sql
	 */
	private function makeSql(){
		if(is_array($this->select)){
			$select = implode(',', $select);
		}else{
			$select = $this->select;
		}
		
		$from = $this->from;

		$where = '0=0';

		if($this->where){
			if(is_array($this->where)){
				foreach ($this->where as $key => $value) {
					// 传递每个查询语句是SQL字符串
					if(is_int($key)){
						$where .= ' AND ' . $value;
					}else{
						// 传递指为数组
						if(is_array($value)){
							$where .= ' AND ' . $key . ' IN (' . implode(',', $value) . ')';
						}elseif(strpos(' ', $where)!==false){
							// 传递值为操作符和值
							$values = explode(' ', $value);
							$where .= ' AND ' . $key . $values[0] . $$values[1];
						}else{
							$where .= " AND $key = '$value'";
						}
					}
				}
			}else{
				$where .= ' AND ' . $this->where;
			}
		}

		$sql = "SELECT $select FROM $from WHERE $where"; 

		if($this->group){
			$sql .= " GROUP BY ".$this->group;
		}

		if($this->order){
			if(is_array($this->order)){
				$order = implode(',', $this->order);
			}else{
				$order = $this->order;
			}

			$sql .= " ORDER BY $order";
		}

		$this->query = $sql;
	}
}
