<?php
define ( "SP_PATH", dirname ( __FILE__ ) . '/SpeedPHP' );
define ( "BASE_PATH", '.' ); /* 主要是给SMARTY模板引用网站资源用的 */
define ( "MODEL_PATH", APP_PATH . "/model" );
define ( "LIB_PATH", APP_PATH . "/business" );
define ( "CONTROLLER_PATH", APP_PATH . "/controller" );
define ( "INCLUDE_PATH", APP_PATH . "/include" );
define ( "UTIL_PATH", APP_PATH . "/util" );
define ( "IMAGE_PATH", BASE_PATH . "/images" );
define ( "CSS_PATH", BASE_PATH . "/css" );
define ( "JS_PATH", BASE_PATH . "/js" );
define ( "RES_PATH", BASE_PATH . "/resource" );

/**
 * *************************report service 相关配置*********************
 */
define ( "SSRS_REPORT_PATH", INCLUDE_PATH . "/SSRSReport/SSRSReport.php" );
define ( "SSRS_REPORT_CONFIG_PATH", APP_PATH . "/SSRSServer.config" );
define ( "REPORT_URL", "http://172.16.81.243:81" );
define ( "NEWS_URL", "http://pj.changyan.com/uploads");
define ( "PIWIK_DOMAIN", "pj.changyan.com");
define ( "PIWIK_URL", "pj.changyan.com/piwik" );
define ( "PIWIK_WEB_ID", "1");

// 题库URL
define ( "TOPIC_URL", "http://192.168.60.115:5050/" );
// 题库系统 内网地址
define ( "TOPIC_URL_LAN", "http://192.168.60.115:5050/" );

return array(
	'mode'=>'debug',
	'default_controller' => 'home_controller', 	// 默认的控制器名称
	'default_action' => 'index',  		// 默认的动作名称
	'url_controller' => 'c',  				// 请求时使用的控制器变量标识
	'url_action' => 'a',  					// 请求时使用的动作变量标识
	'dispatcher_error' => "echo('路由错误，请检查控制器目录下是否存在该控制器/动作。');", // 定义处理路由错误的函数
	'include_path' => array(
		APP_PATH.'/include',
		APP_PATH.'/business',
		APP_PATH.'/util'
	),
	
	//默认数据库
	'db' => array(  // 数据库连接配置
		'driver' => 'mysql',   // 驱动类型
		'host' => '192.168.60.115', // 数据库地址
		'port' => 3306,        // 端口
		'login' => 'root',     // 用户名
		'password' => '123456',      // 密码
		'database' => 'unified_portalnew_2', // 库名称
	),
	
	
	// 视图配置
	'view' => array(
		'enabled' => TRUE, 		// 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/view', 		// 模板目录
			'compile_dir' => APP_PATH.'/tmp',	 			// 编译目录
			'cache_dir' => APP_PATH.'/tmp',		 			// 缓存目录
			'left_delimiter' => '<{',  								// smarty左限定符
			'right_delimiter' => '}>' 								// smarty右限定符
		),
		'debugging' => FALSE, 				// 是否开启视图调试功能，在部署模式下无法开启视图调试功能
		'engine_name' => 'Smarty', 			// 模板引擎的类名称，默认为Smarty
		'engine_path' => SP_PATH.'/Drivers/Smarty/Smarty.class.php', 	// 模板引擎主类路径
		'auto_display' => TRUE, 				// 是否使用自动输出模板功能
		'auto_display_sep' => '/', 				// 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，以此类推
		'auto_display_suffix' => '.html', 	// 自动输出模板的后缀名
	)
);