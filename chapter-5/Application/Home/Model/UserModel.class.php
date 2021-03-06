<?php

/**
 * Created by PhpStorm.
 * User: StringBOX
 * Date: 2017/10/18
 * Time: 14:50
 */

namespace Home\Model;

use Think\Model;

class UserModel extends Model\RelationModel
{
    private $denyUsernames = array(
        'admin',
        'administrator'
    );

    public $_validate = array(
        array('username', 'require', '用户名不能为空'),
        array('password', 'require', '密码不能为空', 1, '', 1),
        array('username', '', '用户名已存在', 0, 'unique', 1),
        array('password', '6,20', '密码长度必须在6-20', 0, 'length'),
        array('password', '/^\w{6,20}$/', '密码格式错误'),
        array('password', 'repassword', '确认密码错误', 0, 'confirm', 1),
        array('username', 'checkUsername', '用户名非法', 0, 'callback'),
    );

    public $_auto = array(
        array('password','md5',self::MODEL_BOTH,'function'),// 新增或编辑的时候是哟个md5函数处理密码
        array('created_at','time',self::MODEL_INSERT,'function'),// 新增的时候将创建时间设为当前时间戳
        array('updated_at','time',self::MODEL_UPDATE,'function')// 更新的时候将更新时间设为当前时间戳
    );

    public $_link = array(
        'extra'=>array(
            'mapping_type'=>self::HAS_ONE,
            'class_name'=>'UserExtra',
            'foreign_key'=>'user_id',
            'mapping_fields'=>'email,qq'
        ),
        'posts' =>array(
            'mapping_type' =>self::HAS_MANY,
            'class_name'=>'Post',
            'foreign_key'=>'user_id'
        )
    );

    /**
     * 检测用户名是否在$denyUsernames中，在则返回false(判断用户名是否可用)
     * @param $username
     * @return bool
     */
    public function checkUsername($username)
    {
        foreach ($this->denyUsernames as $u) {
            if (strpos($username, $u) !== false) {
                return false;
            }
        }
        return true;
    }
}
