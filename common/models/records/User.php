<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $phone
 * @property string $role
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property integer $reg_ip
 * @property integer $last_login_time
 * @property integer $last_login_ip
 *
 * @property ConsumeLog[] $consumeLogs
 * @property NormalUserInfo[] $normalUserInfos
 */
class User extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 1;
    const STATUS_ACTIVED = 2;
    const STATUS_NOT_ACTIVED = 3;

    const ROLE_NORMAL = 1;
    const ROLE_ADMIN = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'salt', 'email', 'phone'], 'required'],
            [['id', 'status', 'reg_ip', 'last_login_time', 'last_login_ip', 'role'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['username', 'password', 'salt', 'email', 'phone'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'salt' => 'Salt',
            'email' => 'Email',
            'phone' => 'Phone',
            'role' => 'Role',
            'create_time' => 'Created Time',
            'update_time' => 'Updated Time',
            'status' => 'Status',
            'reg_ip' => 'Reg Ip',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConsumeLogs()
    {
        return $this->hasMany(ConsumeLog::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormalUserInfos()
    {
        return $this->hasMany(NormalUserInfo::className(), ['user_id' => 'id']);
    }
}
