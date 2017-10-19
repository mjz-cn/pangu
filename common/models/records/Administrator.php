<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%administrator}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $phone
 * @property integer $create_time
 * @property integer $reg_ip
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $update_time
 * @property integer $status
 */
class Administrator extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_NORMAL  = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%administrator}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salt', 'username', 'password', 'phone', 'email'], 'required'],
            [['create_time', 'reg_ip', 'last_login_time', 'last_login_ip', 'update_time', 'status'], 'integer'],
            [['username'], 'string', 'max' => 16],
            [['password'], 'string', 'max' => 60],
            [['salt', 'email'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 15],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
            'phone' => '电话',
            'create_time' => '',
            'reg_ip' => '注册ip地址',
            'last_login_time' => '上次登陆时间',
            'last_login_ip' => '上次登陆IP',
            'update_time' => '更新时间',
            'status' => '状态',
        ];
    }
}
