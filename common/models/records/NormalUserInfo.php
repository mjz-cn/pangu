<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%normal_user_info}}".
 *
 * @property integer $user_id
 * @property string $address
 * @property string $postcode
 * @property integer $relation_pos
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
            [['user_id'], 'required'],
            [['user_id', 'relation_pos'], 'integer'],
            [['address'], 'string', 'max' => 500],
            [['postcode'], 'string', 'max' => 50],
            [['user_id'], 'unique'],
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
            'address' => 'Address',
            'postcode' => 'Postcode',
            'relation_pos' => 'Relation Pos',
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
