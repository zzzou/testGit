<?php
/**
 * 用户表
 *
 * @name 用户表  user
 * @package PJHome
 * @category model
 * @link /model/m_user.php
 * @author guanhan
 * @date 2014-3-20  20:35
 * @version 1.0
 */
class UserModel extends  BaseModel{
	//主键
	public $pk = 'user_id';

	//表名
	public $table = 'user';

    /**
     * 判断用户是否存在
     * @param string $loginName 登录名
     * @return boolean 存在返回true，否则false
     */
    public function isUserExist($loginName) {
        $result = $this->find(array("name"=>$loginName));
        return (isset($result) && $result != false && $result['name'] == $loginName);
    }


    /**
     * 验证用户
     * @param string $loginName 登录名
     * @param string $password 密码
     * @return string|boolean 成功返回用户信息，失败返回false
     */
    public function validateUser($loginName,$password){
        $result = $this->find(array("name"=>$loginName,"pwd"=>$password));
        if(isset($result) && $result != false && count($result) > 0){
            return  $result;
        }
        return false;
    }



    /**
     * 获取用户信息
     * @param int $userId 用户ID
     * @return array|false 成功返回用户信息数组（key=>value）形式，否则false
     */
    public function getUserInfo($userId){
        return $this->find(array("user_id"=>$userId));
    }

    /**
     *
     * @param int $userId 用户ID
     * @param string $loginName 登陆名称
     * @param string $oldPassword 旧密码
     * @param string $newPassword 新密码
     * @return number|boolean 旧密码错误返回 -2,修改错误返回false，修改成功返回true
     */
    public function modifyPassword($userId,$loginName,$oldPassword,$newPassword){
        $result = $this->validateUser($loginName,$oldPassword);
        if(!isset($result) || empty($result)){
            return -2;
        }
        return $this->updateField(array("user_id"=>$userId,"name"=>$loginName,"pwd"=>$oldPassword),'pwd',$newPassword);
    }

    /**
     * 获取某种类型的所有用户
     *
     * @param int $userType 用户类型
     * @return array 用户列表
     */
    public function findAllByType($userType){
        return $this->findAll(array('user_type'=>$userType));
    }
}
/* End of file m_word.php */
/* Location: ./model/m_word.php */