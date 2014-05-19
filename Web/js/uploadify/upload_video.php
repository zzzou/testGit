<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination

$targetFolder = 'upload'; //上传的目标路径
$save_url = '/upload/';


if (!empty($_FILES)) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $targetFolder;
    $targetFile = rtrim($targetPath,'/') . '/';

    // 允许上传的文件后缀名
    $fileTypes =array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb','mp4'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);


    $ymd = date("Ymd");
    $targetFile .= $ymd . "/";
    $save_url .= $ymd . "/";
    if (!file_exists($targetFile)) {
        mkdir($targetFile);
    }
    //新文件名
    $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $fileParts['extension'];

    //移动文件
    $file_path = $targetFile . $new_file_name;
    $file_url = $save_url.$new_file_name;


    if (in_array($fileParts['extension'],$fileTypes)) {
        move_uploaded_file($tempFile,$file_path);
        echo $file_url;
    } else {
        echo 'false';
    }
} else {
    echo 'false';
}

?>