<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%normal_user_info}}".
 *
 * @property integer $user_id
 * @property integer $broker_id
 * @property integer $referrer_id
 * @property string  $real_name
 * @property integer $gender
 * @property string  $card_id
 * @property string  $address
 * @property string  $postcode
 * @property string  $bank_account
 * @property string  $bank_name
 * @property integer $relation_pos
 * @property string  $broker_path
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
            [['user_id', 'broker_id', 'referrer_id', 'real_name', 'gender', 'card_id', 'bank_account', 'bank_name'], 'required'],
            [['user_id', 'broker_id', 'referrer_id', 'gender', 'relation_pos'], 'integer'],
            [['real_name', 'bank_account', 'bank_name', 'broker_path'], 'string', 'max' => 255],
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
            'user_id' => '用户ID',
            'broker_id' => '接点人 ID',
            'referrer_id' => '推荐人 ID',
            'real_name' => '真实姓名',
            'gender' => '性别',
            'card_id' => '身份证号',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'bank_account' => '银行账号',
            'bank_name' => '开户行',
            'relation_pos' => '在接点人系谱图中的位置',
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
