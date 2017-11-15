<?php

namespace common\models\records;

use common\helpers\ArrayHelper;
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
 * @property string $name
 * @property string $phone
 * @property integer $postcode
 *
 * @property User $user
 */
class Address extends \yii\db\ActiveRecord
{

    const USER_ADDRESS_LIMIT = 3;

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
            [['user_id', 'phone', 'name', 'province', 'city', 'area', 'phone', 'postcode'], 'required'],
            [['user_id', 'province', 'city', 'area', 'postcode'], 'integer'],
            [['street', 'name'], 'string', 'max' => 60],
            [['phone', 'name'], 'string', 'max' => 20],
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
            'user_id' => '用户',
            'province' => '省',
            'city' => '市/县',
            'area' => '区/县',
            'street' => '街道',
            'phone' => '电话',
            'name' => '收货人姓名',
            'postcode' => '邮编',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDetailAddress()
    {
        $regions = Region::find()->select('fullname')
            ->where(['code' => [$this->province, $this->city, $this->area]])
            ->orderBy('code asc')
            ->all();

        $da = '';
        foreach ($regions as $region) {
            $da .= $region->fullname . '/';
        }
        return $da . $this->street;
    }
}
