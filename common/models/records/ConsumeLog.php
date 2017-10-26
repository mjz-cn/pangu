<?php

namespace common\models\records;

use backend\models\AdminUser;
use Yii;

/**
 * This is the model class for table "{{%consume_log}}".
 *
 * @property integer $user_id
 * @property integer $consume_type
 * @property integer $currency_type
 * @property double $amount
 * @property integer $from_user_id
 * @property integer $from_admin_id
 * @property string $desc
 * @property integer $create_time
 * @property string $date
 *
 * @property User $user
 */
class ConsumeLog extends \yii\db\ActiveRecord
{

    const CURRENCY_HUOBI = 1;
    const CURRENCY_DIANZIBI = 2;
    const CURRENCY_JIANGJIN = 3;
    const CURRENCY_XIAOFEI = 4;

    const CONSUME_ADMIN = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%consume_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'consume_type', 'currency_type', 'date'], 'required'],
            [['user_id', 'consume_type', 'currency_type', 'from_user_id', 'from_admin_id', 'create_time'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
            [['desc'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'consume_type' => 'Consume Type',
            'currency_type' => 'Currency Type',
            'amount' => 'Amount',
            'from_user_id' => 'From User ID',
            'from_admin_id' => 'From Admin ID',
            'desc' => 'Desc',
            'create_time' => 'Create Time',
            'date' => 'Date',
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);
        if ($result) {
            if (empty($this->from_admin_id) && empty($this->from_user_id) ) {
                $this->addError('from_admin_id from_user_id', '这两者必须设置一个');
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getFromUser() {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
    }

    public function getAdmin() {
        return $this->hasOne(Administrator::className(), ['id' => 'from_admin_id']);
    }

    public static function getCurrencyText($t) {
        $arr = static::getCurrencyArr();
        if (isset($t)) {
            return $arr[$t];
        }
        return '未知';
    }

    public static function getCurrencyArr() {
        return [
            static::CURRENCY_HUOBI => '货币',
            static::CURRENCY_DIANZIBI => '电子币',
            static::CURRENCY_JIANGJIN => '奖金币',
            static::CURRENCY_XIAOFEI => '消费币',
        ];
    }
}
