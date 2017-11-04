<?php

namespace common\behaviors;

use Yii;
use yii\base\Controller;
use yii\web\ForbiddenHttpException;


class RbacBehavior extends \yii\base\Behavior
{

    /**
     * @var array 无需权限检查的action
     */
    public $allowActions = [];

    /**
     * ---------------------------------------
     * 功能说明
     * @return array
     * ---------------------------------------
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'rbacAction',
        ];
    }

    /**
     * ---------------------------------------
     * 控制器执行前的rbac处理
     * @param $event \yii\base\ActionEvent 为什么是ActionEvent而不是Event，
     *        因为yii/base/Controller第269行，事件参数是$event = new ActionEvent($action)
     *
     * 注意：ActionEvent::$isValid参数true/false分别表示继续执行或终止执行action，
     *        所以验证成功后要$event->isValid = true，参考代码yii/base/Controller第152、270行
     * @return boolean
     * ---------------------------------------
     */
    public function rbacAction($event)
    {
        $can = $this->can($event);

        if (!$can) {
            $event->isValid = false; // 终止执行action
            $this->denyAccess();
        }
    }

    /**
     * @param  $event \yii\base\ActionEvent
     * @return bool
     */
    protected function can($event)
    {
        $event->isValid = true; // 继续执行action
        $action = $event->action;
        $rule = $action->getUniqueId();

        $can = false;
        foreach ($this->allowActions as $allow) {
            if (substr($allow, -1) == '*') {
                if (strpos($rule, rtrim($allow, '*')) === 0) {
                    $can = true;
                }
            } else {
                if ($rule == $allow) {
                    $can = true;
                }
            }
        }
        return $can;
    }

    /**
     * Denies the access of the user. HTTP 403 您没有执行此操作的权限
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess()
    {
        if (\Yii::$app->user->getIsGuest()) {
            \Yii::$app->user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


}
