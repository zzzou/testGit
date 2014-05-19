<?php
require_once(APP_PATH . '/business/ModelLib.php');
/**
 * Created by PhpStorm.
 * User: xl
 * Date: 14-3-21
 * Time: 下午4:17
 */

// 自定义导航函数
function smarty_function_load_leftnav($params, $smarty)
{
    $modelIns = spClass("ModelLib");
    // 获取导航栏数据
    $nav_info = $modelIns->getModels($_SESSION['role'], $params['modelID']);
    $cur = $modelIns->getModel($params['modelID']);

    // 声明变量
    $smarty->assign($params['navs'], $nav_info);
    $smarty->assign($params['cur'], $cur);

}

?>