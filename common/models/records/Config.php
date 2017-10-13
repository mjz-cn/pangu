<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $group
 * @property integer $type
 * @property string $value
 * @property string $extra
 * @property string $remark
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $sort
 * @property integer $status
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group', 'type', 'create_time', 'update_time', 'sort', 'status'], 'integer'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['title'], 'string', 'max' => 50],
            [['extra'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'group' => 'Group',
            'type' => 'Type',
            'value' => 'Value',
            'extra' => 'Extra',
            'remark' => 'Remark',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }
}
