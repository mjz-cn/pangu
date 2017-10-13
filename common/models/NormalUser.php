<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/9/29
 * Time: 下午9:47
 */

namespace common\models;


class NormalUser extends BaseUser
{

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result) {
            if ($insert) {
                $this->role = static::ROLE_NORMAL;
            }
        }
        return $result;
    }
}