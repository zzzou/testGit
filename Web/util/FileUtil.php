<?php
/**
 * 文件工具类
 *
 * @package NONE
 * @category include
 * @link /include/Utils.php
 * @author yunyang
 * @version 1.0
 * @created 2013-12-4 10:38:00
 *
 */
class FileUtil {
	
	/**
	 * 创建文件夹
	 * @param string $dir 文件夹路径
	 * @return boolean 成功返回true，否则false
	 */
	public static function  create_folders($dir){
		return is_dir($dir) or (FileUtil::create_folders(dirname($dir)) and mkdir($dir, 0777));
	}
	
}

?>