<?php
/**
  * 获取excel下一列的列名，暂只支持两位字母，不支持多位
  *
  * @param string @name 列名
  * @return string 返回下一列名，例如 A的下一列是B，AC的下一列是AD
  */
function excelNextColumn($name){
    $chars = str_split($name);
    $length = sizeof($chars);
    $last_char = $chars[$length-1];
    $last_ascii = ord($last_char);
    if($last_ascii<90){
        $last_ascii++;
        $chars[$length-1] = chr($last_ascii);
    }else{
        $last_ascii = 65;
        $chars[$length-1] = chr($last_ascii);
        if($length<2){
            array_push($chars, 'A');
        }else{
            $char = $chars[$length-2];
            $ascii = ord($char);
            $ascii++;
            $chars[$length-2] = chr($ascii);
        }
    }

    return implode('', $chars);
}

/**
  * 返回一个随机数字
  *
  */
function getRandomNumber(){
    return time().rand(0,10000);
}
