<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/28
 * Time: 下午10:26
 */

namespace backend\models;


use creocoder\nestedsets\NestedSetsBehavior;

class Tree extends \yii\db\ActiveRecord
{
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public static function tableName()
    {
        return 't_tree1';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
}