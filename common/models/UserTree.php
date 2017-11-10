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

/**
 * This is the model class for table "{{%user_tree}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $tree
 * @property integer $user_id
 */
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
        return '{{%user_tree}}';
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