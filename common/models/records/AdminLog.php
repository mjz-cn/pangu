<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "t_admin_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $controller
 * @property string $action
 * @property string $query_string
 * @property string $remark
 * @property string $ip
 * @property integer $create_time
 * @property integer $status
 */
class AdminLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_admin_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'controller', 'action', 'query_string', 'remark'], 'required'],
            [['user_id', 'create_time', 'status'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['controller', 'action'], 'string', 'max' => 50],
            [['query_string', 'remark'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 15],
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
            'title' => 'Title',
            'controller' => 'Controller',
            'action' => 'Action',
            'query_string' => 'Query String',
            'remark' => 'Remark',
            'ip' => 'Ip',
            'create_time' => 'Create Time',
            'status' => 'Status',
        ];
    }
}
