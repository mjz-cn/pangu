<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/28
 * Time: 下午10:27
 */

namespace backend\models;


use creocoder\nestedsets\NestedSetsQueryBehavior;

class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}