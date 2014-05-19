<?php

define ( "APP_PATH", dirname ( __FILE__ ) ); /* APP_PATH 主要是用于PHPrequire用的 */

$spConfig = require (APP_PATH . '/config.php');

require (SP_PATH . "/SpeedPHP.php");
import ( CONTROLLER_PATH . "/BaseController.php" );
import ( LIB_PATH . "/BaseLib.php" );
import ( MODEL_PATH . "/BaseModel.php" );
import ( UTIL_PATH . "/ArrayUtil.php" );
import ( UTIL_PATH . "/WebUtil.php" );
spRun ();