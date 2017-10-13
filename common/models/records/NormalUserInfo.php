<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%normal_user_info}}".
 *
 * @property integer $user_id
 * @property integer $broder_id
 * @property integer $referrer_id
 * @property string $real_name
 * @property integer $gender
 * @property string $card_id
 * @property string $address
 * @property string $postcode
 * @property string $bank_account
 * @property string $bank_name
 *
 * @property User $user
 */
class NormalUserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%normal_user_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'broder_id', 'referrer_id', 'real_name', 'gender', 'card_id', 'address', 'bank_account'], 'required'],
            [['user_id', 'broder_id', 'referrer_id', 'gender'], 'integer'],
            [['real_name', 'bank_account', 'bank_name'], 'string', 'max' => 255],
            [['card_id'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 500],
            [['postcode'], 'string', 'max' => 50],
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
            'broder_id' => 'Broder ID',
            'referrer_id' => 'Referrer ID',
            'real_name' => 'Real Name',
            'gender' => 'Gender',
            'card_id' => 'Card ID',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'bank_account' => 'Bank Account',
            'bank_name' => 'Bank Name',
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
