<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/14
 * Time: 下午2:57
 */

namespace backend\behaviors;


use yii\base\ActionEvent;
use yii\web\Controller;

class CsrfBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * @param ActionEvent $event
     * @return mixed
     */
    public function beforeAction($event)
    {
        $action= $event->action;
        if (strcasecmp($action->id, 'delete') == 0) {
            $action->controller->enableCsrfValidation = false;
        }

        return $event->isValid;
    }
}