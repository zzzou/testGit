<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination

$targetFolder = 'resource/doc'; //上传的目标路径
$save_url = 'resource/doc';

if (!empty($_FILES)) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $targetFolder;
    $targetFile = rtrim($targetPath,'/') . '/';

    // 允许上传的文件后缀名
    $fileTypes =array('swf','doc','Doc'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);


	$ymd = date("YmdHms");
    $save_url .= $ymd . "/";
    //新文件名
    $file_name = $_FILES['Filedata']['name'];
    $new_file_name = urldecode(iconv ( 'UTF-8' , 'gb2312' , $file_name ));
    $out_file_name = explode(".",$file_name);
 
    //移动文件
    $file_path = $targetFile . $new_file_name;
    $file_url = $targetFile.$file_name;
    if (in_array($fileParts['extension'],$fileTypes)) {
        move_uploaded_file($tempFile,$file_path);
        echo $file_url;
    } else {
        echo 'false';
    }
}

?>