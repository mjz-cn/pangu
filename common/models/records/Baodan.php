<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%baodan}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $baodanbi
 * @property integer $create_time
 * @property integer $status
 */
class Baodan extends \yii\db\ActiveRecord
{
    const STATUS_CHECKING = 0;
    const STATUS_CHECKED = 1;
    const STATUS_REJECT = 2;

    private $_user;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%baodan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'baodanbi', 'create_time', 'status'], 'integer'],
            [['baodanbi', 'status'], 'required'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '报单中心编号',
            'user_id' => '用户账号',
            'baodanbi' => '报单币',
            'create_time' => 'Create Time',
            'status' => '状态 , 0 未审核, 1 已审核',
        ];
    }

    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = $this->hasOne(User::className(), ['id' => 'user_id']);
        }
        return $this->_user;
    }

    public static function getName($id) {
        $model = static::findOne(['id' => $id]);
        if ($model) {
            return $model->name;
        }
        return null;
    }
}
