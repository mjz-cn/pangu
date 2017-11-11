<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 上午12:33
 */

namespace backend\controllers;


use backend\models\search\ActivedUserRatioLogSearch;
use backend\models\search\BonusSearch;
use backend\models\search\ExchangeSearch;
use backend\models\search\CheckInfoSearch;
use backend\models\search\FinanceSearch;
use backend\models\search\JiangjinToDianziSearch;
use backend\models\search\ManageHuobiSearch;
use backend\models\MangageHuobiForm;
use common\controllers\BaseController;
use common\models\records\ActiveUserRatioLog;
use common\models\records\ExchangeLog;
use common\models\search\TransferSearch;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

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
        $model = new MangageHuobiForm();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error(json_encode($model->errors));
            }
        }
        $this->setForward();
        $searchModel = new ManageHuobiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manage_huobi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    // 审核本月要结账的激活用户奖金
    public function actionActivedUserCheck()
    {
        // 激活用户后生成一个表单，表单记录要发放的奖金

        // form
        if (Yii::$app->request->isPost) {
            // id, status
            $model = ActiveUserRatioLog::findOne([
                'id' => Yii::$app->request->get('a_id'),
                'status' => ActiveUserRatioLog::STATUS_CHECKING
            ]);
            $status = Yii::$app->request->get('a_status');
            if ($model) {
                if ($status == ActiveUserRatioLog::STATUS_APPROVE || $status == ActiveUserRatioLog::STATUS_REJECT) {
                    $model->status = $status;
                    // 需要给用户加钱
                    $model->convertToTransactions();
                }
                else {
                    throw new BadRequestHttpException('状态错误');
                }
            } else {
                throw new NotFoundHttpException('有效重复报单记录未找到');
            }
            return $this->redirect($this->getForward());
        }
        // 被激活用户, 奖金用户， 奖金金额，税费，重复消费， 描述, 通过
        $this->setForward();
        $searchModel = new ActivedUserRatioLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('actived_user_check', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}