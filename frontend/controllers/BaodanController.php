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
use common\models\records\RechargeLog;
use common\models\search\RechargeLogSearch;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

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
     * 审核报单
     */
    public function actionCheck()
    {
        // 充值记录
        if (Yii::$app->request->isPost) {
            // id, status
            $model = RechargeLog::findOne(['id' => Yii::$app->request->get('r_id'), 'baodan_status' => RechargeLog::STATUS_CHECKING]);
            $status = Yii::$app->request->get('r_status');
            if ($model) {
                if ($status == RechargeLog::STATUS_APPROVE || $status == RechargeLog::STATUS_REJECT) {
                    $model->baodan_status = $status;
                    $model->update(false, ['baodan_status']);
                }
                else {
                    throw new BadRequestHttpException('状态错误');
                }
            } else {
                throw new NotFoundHttpException('重置记录未找到');
            }
        }
        $searchModel = new RechargeLogSearch();
        $dataProvider = $searchModel->frontendCheckSearch(Yii::$app->request->queryParams);

        return $this->render('check', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return null| NormalUser
     */
    private function getUser()
    {
        $user = \Yii::$app->user->identity;
        return $user;
    }

    public function actionSearch($name)
    {
        $rows = Baodan::find()->select('id, name')->where('name like :name', [
            ':name' => "%" . $name . "%",
        ])->asArray()->all();
        return $this->asJson($rows);
    }
}