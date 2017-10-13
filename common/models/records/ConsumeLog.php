<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "t_consume_log".
 *
 * @property integer $user_id
 * @property integer $consume_type
 * @property integer $currency_type
 * @property string $create_time
 * @property integer $amount
 * @property integer $from_user_id
 * @property string $desc
 *
 * @property User $user
 */
class ConsumeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_consume_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'consume_type', 'currency_type', 'create_time', 'amount', 'from_user_id'], 'required'],
            [['user_id', 'consume_type', 'currency_type', 'amount', 'from_user_id'], 'integer'],
            [['create_time'], 'safe'],
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
            'create_time' => 'Created Time',
            'amount' => 'Amount',
            'from_user_id' => 'From User ID',
            'desc' => 'Desc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
