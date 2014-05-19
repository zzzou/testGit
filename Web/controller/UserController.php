<?php
/**
 * 用户公共控制器
 *
 * @package controller
 * @link /controller/UserController.php
 * @author yunyang
 * @date 2014-3-20
 *
 * @history：
 *
 */
class UserController extends BaseController {
	
    /**
     * 登陆页<br/>
     * 开放性：全局开放<br/>
     * 调用方式：/index.php?c=user_controller&a=login<br/>
     * 参数说明：
     * 提交方式：get
     */
    public function login() {
    	$this->newsUrl = NEWS_URL;
        $this->display('login/index.html');
    }

	/**
	 * 登陆验证<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=user_controller&a=login&username=teacher&password=12345678&code=MUZ1<br/>
	 * 参数说明：username string 用户名称<br/>
	 * password string 用户密码<br/>
	 * code string 验证码 <br/>
	 * 提交方式：get/post
	 */
	public function loginAuth() {
		$username = $this->spArgs ( "username" );
		$password = $this->spArgs ( "password" );
		$vaildateCode = $this->spArgs ( "code" );
		if ($_SESSION ['code'] != $vaildateCode) {
			echo $this->buildResult ( -3, "验证码输入不正确", null );
			return;
		}
		
		if (! $this->userLib->isUserExist( $username )) {
			echo $this->buildResult ( -2, "该用户不存在", null );
			return;
		}
		
		$validateResult = $this->userLib->validateUser( $username,$password );
		if ($validateResult == false) {
			echo $this->buildResult ( -1, "用户名与密码不匹配", null );
			return;
		}
		
		$_SESSION['userInfo'] = $validateResult;
        $_SESSION['role'] = $validateResult['user_type'];
        
        //添加日志
        $user_type=$_SESSION['userInfo']['user_type'];
        $user_own_id=$_SESSION['userInfo']['user_own_id'];
        $action_content="网页登陆";
        $action_desc="登陆成功";
        
		echo $this->buildResult ( 1, "登陆成功",  $this->__getRoleUrl($validateResult['user_type']));
	}
	
	
	/**
	 * 生成验证码<br/>
	 * 开放性：全局开放<br/>
	 * 调用方式：/index.php?c=user_controller&a=getCode<br/>
	 * 参数说明：无<br/>
	 * 提交方式：get<br/>
	 */
	public function getCode() {
		header ( "Content-type: image/PNG" );
		$str = $this->__random ( 4 );
		$width = 60; // 验证码图片的宽度
		$height = 30; // 验证码图片的高度
		
		$_SESSION ["code"] = $str;
		
		$im = imagecreate ( $width, $height );
		// 背景色
		$back = imagecolorallocate ( $im, 0xFF, 0xFF, 0xFF );
		// 模糊点颜色
		$pix = imagecolorallocate ( $im, 187, 230, 247 );
		// 字体色
		$font = imagecolorallocate ( $im, 41, 163, 238 );
		// 绘模糊作用的点
		mt_srand ();
		for($i = 0; $i < 1000; $i ++) {
			imagesetpixel ( $im, mt_rand ( 0, $width ), mt_rand ( 0, $height ), $pix );
		}
		$angle = 30;
		imagestring ( $im, 5, 10, 7, $str, $font );
		// Set the enviroment variable for GD
		//putenv('GDFONTPATH=' . realpath(RES_PATH . '/font'));
		//imagettftext($im,2,10,10,$font,"../resource/font/FORTE.TTF",$str);
		imagerectangle ( $im, 0, 0, $width - 1, $height - 1, $font );
		imagepng ( $im );
		imagedestroy ( $im );
	}
	
	/**
	 * 修改密码<br/>
	 * 开放性:对登录用户开放
	 * 调用方式：/index.php?c=user_controller&a=modifyPassword&username=yourname&oldPassword=yourOldPassword
	 * 				&newPassword=yourNewPassword<br/>
	 * 参数说明：username string 用户登录名<br/>
	 * 		oldPassword string 旧密码 <br/>
	 * 		newPassword string 新密码<br/>
	 * 提交方式：get/post<br/>
	 */
	public function modifyPassword(){
		$username = $this->spArgs ( "username" );
		$oldPassword = $this->spArgs ( "oldPassword" );
		$newPassword = $this->spArgs ( "newPassword" );
		//TODO ：从SESSION里面获取用户名称与上传的用户姓名对比，之后调用后台服务返回相应结果
	}
	
	/**
	 * 退出登陆<br/>
	 * 开放性:对登录用户开放
	 * 调用方式：/index.php?c=user_controller&a=logOut<br/>
	 * 提交方式：get/post<br/>
	 */
	public function logOut(){
		session_destroy();
        header("Location: ./index.php");
	}
	
	/**
	 * 生成随机数
	 * 
	 * @param int $len
	 *        	随机数长度
	 * @return string 随机数
	 */
	private function __random($len) {
		$srcstr = "0123456789";
		mt_srand (); // 配置乱数种子
		$strs = "";
		for($i = 0; $i < $len; $i ++) {
			$strs .= $srcstr [mt_rand ( 0, 9 )];
		}
		return strtoupper ( $strs );
	}
	
	/**
	 * 获取指定用户角色的首页url
	 * @param string $role
	 * @return string $url
	 */
	private function __getRoleUrl($role) {
		switch($role) {
			case '7':
				return './index.php?c=qxjyy_controller&a=markingSystem';
			case '5':
				return './index.php?c=student_controller&a=testEvaluateRep';
			case '4':
				return './index.php?c=question&a=index';
			case '2':
				return './index.php?c=president_controller&a=questionStorageSystem';
            case '6':
                return './index.php?c=admin_controller&a=devRep';
            case '8':
                return './index.php?c=dsjyy_controller&a=markingSystem';
            case '9':
                return './index.php?c=qxjyszr_controller&a=questionStorageSystem';
            case '10':
                return './index.php?c=dsjyszr_controller&a=questionStorageSystem';
            case '11':
                return './index.php?c=xkzz_controller&a=testEvaluateRep';
            case '12':
                return './index.php?c=njzz_controller&a=testEvaluateRep';
            case '3':
                return './index.php?c=bzr_controller&a=getScaleLibrary';
		}
	}
	
	public function qrCodeLogin(){
		$username = $this->spArgs ( "username" );
		$password = base64_decode($this->spArgs ( "password" ));
		if (! $this->userLib>isUserExist( $username )) {
			echo $this->buildResult ( -2, "该用户不存在", null );
			return;
		}
		
		$validateResult = $this->userLib->validateUser( $username,$password );
		if ($validateResult == false) {
			echo $this->buildResult ( -1, "用户名与密码不匹配", null );
			return;
		}
		
		$_SESSION['userInfo'] = $validateResult;
		$_SESSION['role'] = $validateResult['user_type'];
		
		//添加日志
		$user_type=$_SESSION['userInfo']['user_type'];
		$user_own_id=$_SESSION['userInfo']['user_own_id'];
		$action_type=lib_log::ACTION_QRCODE_LOGIN;
		$action_content="二维码登陆登陆";
		$action_desc="二维码登陆成功";

		$this->reportType = 'ps_math';
		$this->display('./module/report/html_reports/ps_math/main/index.html');
	}
}