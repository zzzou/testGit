<?php
/**
  * 数组转化成报表所需的xml格式
  * 
  * @param array $data 数组
  * @param string $tag tag
  *	@return string xml文件内容
  */
function array2xml($data, $tag = ''){
	$xml = '';
    
	foreach($data as $key => $value){
		if(is_numeric($key)){
			if(is_array($value)){
				$xml .= "<$tag>";
				$xml .= array2xml($value);
				$xml .="</$tag>\n";
			}
			else{
				$xml .= "<$tag>$value</$tag>\n";
			}
		}
		else{
			if(is_array($value)){
				$keys = array_keys($value);
				if(is_numeric($keys[0])){
					$xml .=array2xml($value,$key);
				}else{
					$xml .= "<$key>";
					$xml .=array2xml($value);
					$xml .= "</$key>\n";
				}
			 
			}else{
				$xml .= "<$key>$value</$key>\n";
			}
		}
	}

	return $xml;
}

