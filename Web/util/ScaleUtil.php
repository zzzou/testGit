<?php
/**
 * 心理量表常用计算方法
 *
 * @package lib
 * @author 宋利鹏 lpsong@iflytek.com
 * @date 2014-3-24
 */
class ScaleUtil{
	
	/**
	 * 根据数组键值列表，计算数组综合
	 * 
	 * @param array $array 数组
	 * @param array $keys 键值数组
	 * @return int 返回数组数据总和
	 */
	function calArraySum($array, $keys){
		$sum = 0;
		foreach ($keys as $key) {
			$sum += $array[$key];
		}

		return $sum;
	}
}
