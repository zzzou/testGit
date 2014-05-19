<?php
ob_clean();
require_once(APP_PATH . '/include/phpqrcode/phpqrcode.php');
require_once(APP_PATH . '/util/FileUtil.php');
ob_end_clean();

/**
 * 二维码工具类
 *
 * @package NONE
 * @category include
 * @link /include/util/QRCode.php
 * @author yunyang
 * @version 1.0
 * @created 2013-12-4 10:38:00
 *
 */
class QRCodeUtil {
	// TODO - Insert your code here
	function __construct() {
		// TODO - Insert your code here
	}
	
	/**
	 * 生成二维码
	 * @param string $text 二维码内容
	 * @param string $outfile 相对网站根目录的路径 eg：'/resource/qrcode'
	 * @param int $level 二维码清晰度
	 * @param int $size 二维码大小
	 * @param int $margin 边距
	 * @param boolean $saveandprint
	 * @return string|null 二维码生成成功返回网站中二维码相对地址，否则返回null;
	 */
	public static function buildQRCode($text, $folderName){
	
		$path = APP_PATH ."/". $folderName;
		$result = FileUtil::create_folders($path);
		if(!$result){
			return  null;
		}
		
		//拼装文件路径
		//$guid = uniqid('',true);
		$guid = 'demo';
		$path = $path . '/' . $guid . '.png';
		QRcode::png($text, $path, QR_ECLEVEL_M, 200, 2, false);
		return  $folderName . '/' . $guid . '.png';;
	}
	
	
}

?>