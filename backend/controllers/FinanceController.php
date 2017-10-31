<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 上午12:33
 */

namespace backend\controllers;


use backend\models\search\BonusSearch;
use backend\models\search\ExchangeSearch;
use backend\models\search\CheckInfoSearch;
use backend\models\search\FinanceSearch;
use backend\models\search\JiangjinToDianziSearch;
use backend\models\search\ManageHuobiSearch;
use backend\models\MangageHuobiForm;
use common\models\records\ExchangeLog;
use common\models\search\TransferSearch;
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

        $this->setForward();

        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('transfer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 奖金转电子币管理
     */
    public function actionBonusToDianzibi()
    {
        $this->setForward();

        $searchModel = new JiangjinToDianziSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('jiangjin_to_dz', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 货币提现管理 - 申请记录
     */
    public function actionExchange()
    {
        if (Yii::$app->request->isPost) {
            $msg = ExchangeLog::exchange(Yii::$app->request->get('eid'), Yii::$app->request->get('status'));
            if (empty($msg)) {
                $this->success('审核成功');
            } else {
                $this->error($msg);
            }
        }
        $searchModel = new ExchangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('exchange', [
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
}