<?php
/**
  * 转化数组到map
  *
  * @param array $array 数组
  * @param string $key_name key名称
  * @param string|null $value_name 值的名称
  * @param array 返回转化后为map的数组
  */
function arrayToMap($array, $key_name, $value_name=null){
	$result = array();
	foreach ($array as $value) {
		$result[$value[$key_name]] = $value_name ? $value[$value_name] : $value;
	}

	return $result;
}

/**
  * 转化数组下数组中的一个字段到新的数组
  *
  * @param array $array 数组
  * @param string $key_name key名称
  * @param array 返回转化后的数组
  */
function arrayFilter($array, $key_name){
    $result = array();
    foreach ($array as $value) {
        $result[] = $value[$key_name];
    }

  return $result;
}

function arrayMMergeSelf($array){
    $result = array();
    foreach ($array as $value) {
        $result = array_merge($result, $value);
    }

    return $result;
}

/**
  * 合并两个数组成为一个map
  *
  * @param array @keys
  * @param array $values
  * @return array 
  */
function arrayMergeToMap($keys, $values){
    if(count($keys) != count($values)){
        return false;
    }

    $map = array();
    foreach ($keys as $i => $key) {
        $map[$key] = $values[$i];
    }

    return $map;
}

/**
  * 判断一个数组中的所有属性以及值是不是在目标类中
  *
  * @param array $maps 原始数组
  * @param array $array 目标数组
  * @return boolean 存在返回true，否则false
  */
function array_key_value_exists($maps,$array){
    
    foreach ($maps as $key => $value) {
 		if(!isset($array[$key]) || $array[$key] != $value) {
 			return false;
 		}
 	}
 	return true;
}