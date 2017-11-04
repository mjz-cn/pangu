<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/31
 * Time: 下午3:42
 */

namespace frontend\behaviors;

class RbacBehavior extends \common\behaviors\RbacBehavior
{

    protected function can($event)
    {
        $can = parent::can($event);
        if (!$can && !\Yii::$app->user->getIsGuest()) {
            $can = true;
        }
        return $can;
    }
}