<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $street
 * @property integer $phone
 * @property integer $postcode
 *
 * @property User $user
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'phone'], 'required'],
            [['user_id', 'province', 'city', 'area', 'phone', 'postcode'], 'integer'],
            [['street'], 'string', 'max' => 60],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'street' => 'Street',
            'phone' => 'Phone',
            'postcode' => 'Postcode',
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
