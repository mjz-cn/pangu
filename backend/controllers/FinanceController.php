<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 上午12:33
 */

namespace backend\controllers;


use backend\models\search\BonusSearch;
use Yii;

class FinanceController extends BaseController
{

    public function actionIndex() {

    }


    public function actionBonus() {
        $this->setForward();

        $searchModel = new BonusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('bonus', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransfer() {

    }

    public function actionHuobi() {

    }

    public function actionHuobiManage() {

    }

}