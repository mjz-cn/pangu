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
 * @property integer $role
 * @property integer $reg_ip
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property string $image
 * @property integer $broker_id
 * @property string $broker_path
 * @property integer $referrer_id
 * @property string $real_name
 * @property integer $gender
 * @property string $card_id
 * @property string $bank_account
 * @property string $bank_name
 * @property integer $status
 * @property integer $update_time
 * @property integer $create_time
 *
 * @property Address[] $addresses
 * @property ConsumeLog[] $consumeLogs
 * @property NormalUserInfo $normalUserInfo
 */
class User extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_NORMAL  = 1;
    const STATUS_ACTIVED = 2;
    const STATUS_NOT_ACTIVED = 3;

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
            [['username', 'password', 'salt', 'email', 'real_name', 'gender', 'card_id', 'bank_account', 'bank_name'], 'required'],
            [['role', 'reg_ip', 'last_login_time', 'last_login_ip', 'broker_id', 'referrer_id', 'gender', 'status', 'update_time', 'create_time'], 'integer'],
            [['username', 'password', 'salt', 'email', 'image', 'real_name', 'bank_account', 'bank_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['broker_path', 'card_id'], 'string', 'max' => 20],
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
            'username' => '用户名',
            'password' => '密码',
            'salt' => 'Salt',
            'email' => '邮箱',
            'phone' => '电话',
            'role' => 'Role',
            'reg_ip' => '注册IP',
            'last_login_time' => '上一次登陆时间',
            'last_login_ip' => '上一次登陆IP',
            'image' => 'Image',
            'broker_id' => '接点人',
            'broker_path' => '系谱图路径',
            'referrer_id' => '推荐人',
            'real_name' => '真实姓名',
            'gender' => '性别',
            'card_id' => '身份证号',
            'bank_account' => '银行账号',
            'bank_name' => '开户行',
            'status' => 'Status',
            'update_time' => '更新时间',
            'create_time' => '注册时间',
        ];
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result) {
            if ($insert) {
                $this->create_time = time();
            } else {
                $this->update_time = time();
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConsumeLogs()
    {
        return $this->hasMany(ConsumeLog::className(), ['user_id' => 'id']);
    }

    /**
     * 获取推荐人
     */
    public function getReferrer()
    {
        if (empty($this->referrer_id)) {
            return null;
        }
        return static::findOne(['id' => $this->referrer_id]);
    }

    /**
     * 获取接点人
     */
    public function getBroker()
    {
        if (empty($this->broker_id)) {
            return null;
        }
        return static::findOne(['id' => $this->broker_id]);
    }

    public function getStatusText() {
        if ($this->status == static::STATUS_NORMAL) {
            return '正常';
        }
        return static::getStatusArr()[$this->status];
    }

    public static function getStatusArr() {
        return [
            static::STATUS_DELETED => '封禁',
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

    public static function getUserName($id) {
        if ($id === 0) {
            return '';
        }
        $model = static::find()->select('username')->where(['id' => $id])->one();
        if ($model) {
            return $model->username;
        }
        return '';
    }
}
