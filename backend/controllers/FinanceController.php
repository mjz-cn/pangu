<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 上午12:33
 */

namespace backend\controllers;


use backend\models\search\BonusSearch;
use backend\models\search\FinanceSearch;
use backend\models\search\ManageHuobiSearch;
use backend\models\MangageHuobiForm;
use Yii;

class FinanceController extends BaseController
{

    public function actionIndex()
    {
        $this->setForward();

        $searchModel = new FinanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * 个人奖金查询
     */
    public function actionBonus()
    {
        $this->setForward();

        $searchModel = new BonusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('bonus', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 转账管理
     */
    public function actionTransfer()
    {
        return $this->render('transfer', [

        ]);
    }

    /**
     * 奖金转电子币管理
     */
    public function actionBonusToDianzibi()
    {
        return $this->render('b_to_dz', [

        ]);
    }

    /**
     * 货币提现管理
     */
    public function actionCheckHuobi()
    {
        return $this->render('check_huobi', [

        ]);
    }

    /**
     * 增减会员货币
     */
    public function actionManageHuobi()
    {
        $this->setForward();
        $model = new MangageHuobiForm();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error(json_encode($model->errors));
            }
        }
        $searchModel = new ManageHuobiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manage_huobi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * 审核状况
     */
    public function actionCheckInfo()
    {
        return $this->render('check_info', [

        ]);
    }
}