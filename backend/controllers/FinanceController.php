<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 上午12:33
 */

namespace backend\controllers;


use backend\models\search\BonusSearch;
use backend\models\search\CheckHuobiSearch;
use backend\models\search\CheckInfoSearch;
use backend\models\search\FinanceSearch;
use backend\models\search\ManageHuobiSearch;
use backend\models\MangageHuobiForm;
use Yii;

class FinanceController extends BaseController
{

    public function actionIndex($detail_type = '')
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
     * 货币提现管理 - 申请记录
     */
    public function actionCheckHb()
    {
        $searchModel = new CheckHuobiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('check_hb', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 货币提现管理 － 提现记录
     */
    public function actionCheckedHb()
    {
        $searchModel = new CheckHuobiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('checked_hb', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
     * 审核状况 未审核
     */
    public function actionCheckInfo()
    {
        $searchModel = new CheckInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('check_info', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 审核状况 已审核
     */
    public function actionCheckedInfo()
    {
        $searchModel = new CheckInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('checked_info', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}