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
 * @property integer $amount
 * @property integer $from_user_id
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
            [['user_id', 'consume_type', 'currency_type', 'amount', 'from_user_id', 'date'], 'required'],
            [['user_id', 'consume_type', 'currency_type', 'amount', 'from_user_id', 'create_time'], 'integer'],
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
            'desc' => 'Desc',
            'create_time' => 'Create Time',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAdmin() {
        return $this->hasOne(AdminUser::tableName(), ['id' => 'from_user_id']);
    }
}
