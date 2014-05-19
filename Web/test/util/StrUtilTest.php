<?php
include_once(APP_PATH . '/util/StrUtil.php');

class StrUtilTest extends UnitTestCase {
    function testExcelNextColumn() {
    	$this->assertEqual(excelNextColumn('A'), 'B');
    	$this->assertEqual(excelNextColumn('B'), 'C');
    	$this->assertEqual(excelNextColumn('Z'), 'AA');
    	$this->assertEqual(excelNextColumn('AA'), 'AB');
    	$this->assertEqual(excelNextColumn('BC'), 'BD');
    }
}