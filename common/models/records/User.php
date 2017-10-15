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
    const STATUS_NORMAL  = 1;
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
            [['username', 'password', 'salt', 'email', 'phone', 'status'], 'required'],
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

    public function getStatusText() {
        return static::getStatusArr()[$this->status];
    }

    public static function getStatusArr() {
        return [
            static::STATUS_DELETED => '封禁',
            static::STATUS_NORMAL => '正常',
            static::STATUS_ACTIVED => '已激活',
            static::STATUS_NOT_ACTIVED => '未激活'
        ];
    }

    /**
     * 封禁用户
     * @param array $ids
     */
    public static function banUsers($ids) {
        $ids_str = implode(',', $ids);
        return User::updateAll(['status' => static::STATUS_DELETED], "id in ($ids_str)");
    }

    /**
     * 解封用户
     * @param array $ids
     */
    public static function UnbanUsers($ids) {
        $ids_str = implode(',', $ids);
        return User::updateAll(['status' => static::STATUS_NORMAL], "id in ($ids_str)");
    }

    public static function activeUsers($ids) {
        $ids_str = implode(',', $ids);
        return User::updateAll(['status' => static::STATUS_ACTIVED], "id in ($ids_str)");
    }
}
