<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/9/29
 * Time: 下午9:47
 */

namespace common\models;


use common\models\records\Address;
use common\models\records\NormalUserInfo;

class NormalUser extends BaseUser
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormalUserInfo()
    {
        return $this->hasOne(NormalUserInfo::className(), ['user_id' => 'id']);
    }

    public function getAddresses() {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }
}