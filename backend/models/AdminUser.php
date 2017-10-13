<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/9/28
 * Time: 下午9:41
 */

namespace backend\models;


use common\models\BaseUser;

class AdminUser extends BaseUser
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert) ) {
            $this->role = static::ROLE_ADMIN;
            return true;
        }
        return false;
    }
}