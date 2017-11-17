<?php

namespace backend\controllers;

use backend\models\search\BaodanMembersSearch;
use common\controllers\BaseController;
use common\models\records\RechargeLog;
use common\models\search\RechargeLogSearch;
use Yii;
use common\models\records\Baodan;
use backend\models\search\BaodanSearch;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BaodanController implements the CRUD actions for Baodan model.
 */
class BaodanController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'check' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Baodan models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            // id, status
            $status = Yii::$app->request->get('baodan_status');
            $conditions = ['id' => Yii::$app->request->get('baodan_id')];
            if ($status == Baodan::STATUS_CHECKING) {
                // 审核未审核的报单中心申请
                if ($status == Baodan::STATUS_APPROVE || $status == Baodan::STATUS_REJECT) {
                    $conditions[] = ['status' => Baodan::STATUS_CHECKING];
                } else {
                    throw new BadRequestHttpException('状态错误');
                }
            } elseif ($status == Baodan::STATUS_REJECT) {
                // 冻结已经通过审核的报单中心
                $conditions[] = ['status' => Baodan::STATUS_APPROVE];
            }

            $model = Baodan::findOne($conditions);
            if ($model === null) {
                throw new NotFoundHttpException('未找到有效报单中心');
            }
            $model->status = $status;
            $model->update(false, ['status']);

            return $this->redirect($this->getForward());
        }
        $this->setForward();

        $searchModel = new BaodanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // 审核重复报单
    public function actionRepeatCheck()
    {
        if (Yii::$app->request->isPost) {
            // id, status
            $model = RechargeLog::findOne([
                'id' => Yii::$app->request->get('r_id'),
                'baodan_status' => RechargeLog::STATUS_APPROVE,
                'status' => RechargeLog::STATUS_CHECKING
            ]);
            $status = Yii::$app->request->get('r_status');
            if ($model) {
                if ($status == RechargeLog::STATUS_APPROVE || $status == RechargeLog::STATUS_REJECT) {
                    $model->status = $status;
                    $model->check();
                }
                else {
                    throw new BadRequestHttpException('状态错误');
                }
            } else {
                throw new NotFoundHttpException('有效报单记录未找到, 请检查此次报单是否经过领路老师审核');
            }
            return $this->redirect($this->getForward());
        }

        $this->setForward();

        $searchModel = new RechargeLogSearch();
        $searchModel->baodan_status = RechargeLog::STATUS_APPROVE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('repeat_check', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // 查看报单中心会员
    public function actionMembers()
    {
        $this->setForward();

        $searchModel = new BaodanMembersSearch();
        $searchModel->bd_id = Yii::$app->request->get('id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('members', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Baodan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Baodan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Baodan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Baodan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Baodan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Baodan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSearch($name)
    {
        $rows = Baodan::find()->select('id, name')->where('name like :name', [
            ':name' => "%" . $name . "%",
        ])->asArray()->all();
        return $this->asJson($rows);
    }
}
