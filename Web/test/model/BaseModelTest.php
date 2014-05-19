<?php
import(MODEL_PATH . "/BaseModel.php");

class UserModel extends  BaseModel{
	//主键
	public $pk = 'user_id';

	//表名
	public $table = 'user';
}

class BaseModelTest extends UnitTestCase {
    function testQuery() {
    	$userModel = new UserModel();
    	$result = $userModel->all();
    	$this->assertEqual(sizeof($result), sizeof($userModel->findAll()));

    	$result = $userModel->first();
    	$this->assertEqual(sizeof($result), sizeof($userModel->find()));
    	
    	$page = $userModel->page(1, 10);
    	$this->assertEqual($page['total_count'], 17);
    	$this->assertEqual(sizeof($page['data']), 10);
    	
    	$page = $userModel->page(2, 10);
    	$this->assertEqual($page['total_count'], 17);
    	$this->assertEqual(sizeof($page['data']), 7);
    	
    	$page = $userModel->where('user_id>5')->page(2, 10);
    	$this->assertEqual($page['total_count'], 12);
    	$this->assertEqual(sizeof($page['data']), 2);

    	$result = $userModel->order('user_id DESC')->first();
    	$this->assertEqual($result['user_id'], 18);

    	$result = $userModel->group('user_type')->all();
    	$this->assertEqual(sizeof($result), 11);

    	$result = $userModel->from('user LEFT JOIN usertype ON user.user_type=usertype.user_type')->all();
    	$this->assertEqual(sizeof($result), 17);
    	$this->assertEqual(isset($result['user_type_name']));

        $result = $userModel->findAllByUserType(4);
        $this->assertEqual(sizeof($result), 3);
    }
}