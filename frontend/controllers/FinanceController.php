<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/1
 * Time: 下午2:00
 */

namespace frontend\controllers;


use backend\models\search\ExchangeSearch;
use backend\models\search\FinanceSearch;
use backend\models\search\JiangjinToDianziSearch;
use common\controllers\BaseController;
use common\models\records\ExchangeLog;
use common\models\records\Wallet;
use common\models\search\TransferSearch;
use frontend\models\JiangjinToDianziForm;
use frontend\models\TransferForm;
use Yii;

class FinanceController extends BaseController
{

    /**
     * 奖金明细, 个人收入
     */
    public function actionIndex()
    {
        $this->setForward();

        $searchModel = new FinanceSearch();
        $dataProvider = $searchModel->frontendSearch(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 帐户提现
     */
    public function actionExchange()
    {
        $this->setForward();

        // 展示提现界面
        $exchangeModel = new ExchangeLog();
        if (Yii::$app->request->isPost) {
            $exchangeModel->user_id = Yii::$app->user->getId();
            if ($exchangeModel->load(Yii::$app->request->post()) && $exchangeModel->save()) {
                return $this->refresh();
            }
            echo json_encode($exchangeModel->errors);exit;
        }

        // 提现记录
        $searchModel = new ExchangeSearch();
        $dataProvider = $searchModel->frontendSearch(Yii::$app->request->queryParams);

        return $this->render('exchange', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $exchangeModel,
            'balance' => 1000.0
        ]);
    }

    /**
     * 帐户转账
     */
    public function actionTransfer()
    {
        // 转账记录
        $this->setForward();

        // 展示提现界面
        $transferModel = new TransferForm();
        if (Yii::$app->request->isPost) {
            if ($transferModel->load(Yii::$app->request->post()) && $transferModel->save()) {
                return $this->refresh();
            }
        }

        // 提现记录
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->frontendSearch(Yii::$app->request->queryParams);
        $wallet = Wallet::getValidWallet(\Yii::$app->user->identity->getId());

        return $this->render('transfer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'transferModel' => $transferModel,
            'dianziBalance' => $wallet->dianzi,
        ]);
    }

    /**
     * 奖金币转换
     */
    public function actionBonusToDianzibi()
    {
        $this->setForward();

        $form = new JiangjinToDianziForm();
        if (Yii::$app->request->isPost) {
            if ($form->load(Yii::$app->request->post()) && $form->save()) {
                return $this->refresh();
            }
        }

        $wallet = Wallet::getValidWallet(\Yii::$app->user->identity->getId());

        $searchModel = new JiangjinToDianziSearch();
        $dataProvider = $searchModel->frontendSearch(Yii::$app->request->queryParams);

        return $this->render('jiangjin_to_dz', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $form,
            'jiangjinBalance' => $wallet->jiangjin,
            'dianzibiBalance' => $wallet->dianzi
        ]);
    }

    /**
     * 充值记录
     */
    public function actionRechargeInfo()
    {
        // 暂无
    }
}