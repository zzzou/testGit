<?php
define("APP_PATH", dirname(__FILE__));

// 获取运行模式
$spConfig = require(dirname(__FILE__)  . '/config.php');

require(SP_PATH . "/SpeedPHP.php");

import(LIB_PATH . "/BaseLib.php");

if(isset($spConfig['mode']) && $spConfig['mode']=='debug'){
    require_once(APP_PATH . '/include/simpletest/autorun.php');

    $test = isset($_GET['test']) && $_GET['test'] ? $_GET['test'] : 'all';

    if($test=='all'){
        class AllTests extends TestSuite {
            function AllTests() {
                $this->TestSuite('All tests');
                $this->addAllTests(APP_PATH . '/test/',
                    new SimplePatternCollector('/Test.php$/'));
            }

            function addAllTests($dir, $collector){
                $this->collect($dir,
                    new SimplePatternCollector('/Test.php$/'));

                $dirs = scandir($dir);

                foreach ($dirs as $sub_dir) {
                    if($sub_dir!='.' && $sub_dir!='..'){
                        $current_dir = $dir . '/' . $sub_dir;
                        if(is_dir($current_dir)){
                            $this->addAllTests($current_dir, $collector);
                        }
                    }
                }
            }
        }
    }else{
        $test = APP_PATH . '/test/' . $test . '.php';
        if(file_exists($test)){
            import($test);
        }else{
            exit('test not find');
        }
    }
}else{
    exit('非开发模式，不能运行测试');
}


