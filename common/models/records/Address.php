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
            [['user_id'], 'required'],
            [['user_id', 'province', 'city', 'area'], 'integer'],
            [['street'], 'string', 'max' => 60],
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
        ];
    }
}
