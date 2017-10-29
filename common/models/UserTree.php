<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/29
 * Time: 下午6:21
 */

namespace common\models;


use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class UserTree extends ActiveRecord
{
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }

    public static function tableName()
    {
        return 't_user_tree';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new UserTreeQuery(get_called_class());
    }
}