<?php
require_once(APP_PATH . '/business/ModelLib.php');
/**
 * Created by PhpStorm.
 * User: xl
 * Date: 14-3-21
 * Time: 下午4:17
 */

// 自定义导航函数
function smarty_function_load_topnav($params, $smarty)
{
    $modelIns = spClass("ModelLib");
    $role = $_SESSION['role'];
    // 获取导航栏数据
    $nav_info = $modelIns->getTopModels($role);
    $topModel = $modelIns->getTopModel($role, $params['modelID']);

    // 声明变量
    $smarty->assign($params['navs'], $nav_info);
    $smarty->assign($params['cur'], $topModel);
    $smarty->assign('username', $_SESSION['userInfo']['pj_name']);
}

?>