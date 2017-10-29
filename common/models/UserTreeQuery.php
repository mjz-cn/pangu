<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/29
 * Time: 下午6:22
 */

namespace common\models;


use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class UserTreeQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}