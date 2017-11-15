<?php

namespace frontend\controllers;

use common\controllers\BaseController;
use Yii;
use common\models\records\Address;
use common\models\search\AddressSearch;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends BaseController
{
    /**
     * Lists all Address models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $cnt = Address::find()->where(['user_id' => Yii::$app->user->getId()])->count();

        return $this->render('@common/views/address/index', [
            'dataProvider' => $dataProvider,
            'showCreateBtn' => $cnt < Address::USER_ADDRESS_LIMIT
        ]);
    }

    /**
     * Displays a single Address model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('@common/views/address/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Address model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $cnt = Address::find()->where(['user_id' => Yii::$app->user->getId()])->count();
        if ($cnt >= Address::USER_ADDRESS_LIMIT) {
            throw new BadRequestHttpException('收获地址最多只能有三个');
        }
        $model = new Address();
        $model->phone = Yii::$app->user->identity->phone;
        $model->name = Yii::$app->user->identity->username;
        $model->user_id = Yii::$app->user->getId();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Address model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Address model.
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
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Address::findOne($id)) !== null && $model->user_id == Yii::$app->user->getId()) {
            return $model;
        } else {
            throw new NotFoundHttpException('收货地址不存在');
        }
    }
}
