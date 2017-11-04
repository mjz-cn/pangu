<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/1
 * Time: 下午1:59
 */

namespace frontend\controllers;


use common\controllers\BaseController;
use common\models\NormalUser;
use common\models\records\Baodan;

class BaodanController extends BaseController
{

    /**
     * 报单申请中心
     */
    public function actionRegister()
    {
        $user = $this->getUser();
        $model = $user->getBaodan();
        if ($model == null) {
            $model = new Baodan();
            $model->baodanbi = 0;
            $model->user_id = \Yii::$app->user->identity->getId();
            $model->name = $user->username;
            $model->status = Baodan::STATUS_CHECKING;
        }

        if (\Yii::$app->request->isPost) {
            $model->create_time = time();
            // 检测电子币数量是否足够
            $model->save();
        }

        return $this->render('register', ['model' => $model]);
    }


    /**
     * @return null| NormalUser
     */
    private function getUser() {
        $user = \Yii::$app->user->identity;
        return $user;
    }

}