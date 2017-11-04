<?php

namespace backend\controllers;

use backend\models\ActiveUserForm;
use backend\models\RelationGraphForm;
use common\controllers\BaseController;
use common\models\NormalUser;
use common\models\records\User;
use common\models\search\NormalUserSearch;
use common\helpers\Constants;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * 用户控制器
 * @author longfei <phphome@qq.com>
 */
class UserController extends BaseController
{
    /**
     * ---------------------------------------
     * 构造方法
     * ---------------------------------------
     */
    public function init()
    {
        parent::init();
    }

    /**
     * ---------------------------------------
     * 用户列表
     * ---------------------------------------
     */
    public function actionIndex()
    {
        /* 添加当前位置到cookie供后续操作调用 */
        $this->setForward();

        $searchModel = new NormalUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 激活用户
     */
    public function actionActived()
    {
        /* 添加当前位置到cookie供后续操作调用 */
        $this->setForward();

        $searchModel = new NormalUserSearch();
        $searchModel->is_actived = Constants::NUMBER_TRUE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 未激活用户
     */
    public function actionUnActived()
    {
        /* 添加当前位置到cookie供后续操作调用 */
        $this->setForward();

        $searchModel = new NormalUserSearch();
        $searchModel->is_actived = Constants::NUMBER_FALSE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 激活用户
     */
    public function actionActive()
    {
        $model = new ActiveUserForm();
        $model->setAttributes(Yii::$app->request->get());
        if ($model->validate() && $model->active()) {
            $this->success('激活成功', $this->getForward(), true);
        } else {
            $this->error(json_encode($model->errors), '', true);
        }
    }

    /**
     * ---------------------------------------
     * 添加
     * ---------------------------------------
     */
    public function actionAdd()
    {
        $userModel = new NormalUser();
        $userModel->loadDefaultValues();
        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post($userModel->formName());
            $data['create_time'] = time();
            $data['reg_ip'] = ip2long(Yii::$app->request->getUserIP());
            $data['last_login_time'] = 0;
            $data['last_login_ip'] = ip2long(Yii::$app->request->getUserIP());
            $data['update_time'] = 0;
            /* 表单数据加载和验证，具体验证规则在模型rule中配置 */
            /* 密码单独验证，否则setPassword后密码肯定符合rule */
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $this->error('密码为空或小于6字符');
            }

            $userModel->setAttributes($data);
            $userModel->generateAuthKey();
            $userModel->setPassword($data['password']);
            if ($userModel->broker_id == null) {
                $userModel->broker_id = 0;
            }
            if ($userModel->referrer_id == null) {
                $userModel->referrer_id = 0;
            }

            /* 保存用户数据到数据库 */
            if ($userModel->save()) {
                $this->success('操作成功', '/user/index');
            } else {
                $errors = array_merge([], $userModel->errors);
                $this->error(json_encode($errors));
            }
        }

        return $this->render('edit', [
            'model' => $userModel
        ]);
    }

    /**
     * ---------------------------------------
     * 编辑
     * ---------------------------------------
     */
    public function actionEdit($id)
    {
        $userModel = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post($userModel->formName());
            $data['update_time'] = time();
            /* 如果设置密码则重置密码，否则不修改密码 */
            if (!empty($data['password'])) {
                $userModel->generateAuthKey();
                $userModel->setPassword($data['password']);
            }
            unset($data['password']);

            $userModel->setAttributes($data);
            if ($userModel->broker_id == null) {
                $userModel->broker_id = 0;
            }
            if ($userModel->referrer_id == null) {
                $userModel->referrer_id = 0;
            }
            /* 保存用户数据到数据库 */
            if ($userModel->save()) {
                $this->success('操作成功', '/user/index');
            } else {
                $errors = array_merge([], $userModel->errors);
                $this->error(json_encode($errors));
            }
        }

        return $this->render('edit', [
            'model' => $userModel
        ]);
    }

    /**
     * ---------------------------------------
     * 删除
     * ---------------------------------------
     */
    public function actionDelete()
    {
        $uid = Yii::$app->request->get('id');
        $rowCnt = User::updateAll(['status' => User::STATUS_DELETED], ['id' => $uid]);

        // 也要删除用此用户相关的信息
        // 由于与此用户关联的信息过多，目前只更新用户的状态为封禁状态
        if ($rowCnt > 0) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败！');
        }
    }

    public function actionCheckBan($id)
    {
        $model = $this->findModel($id);
        $model->is_baned = $model->is_baned == Constants::NUMBER_FALSE ?
            Constants::NUMBER_TRUE : Constants::NUMBER_FALSE;
        $rowCnt = $model->update(false, ['is_baned']);
        if ($rowCnt == 1) {
            $this->success('操作成功', $this->getForward(), true);
        } else {
            $this->error(json_encode($model->errors), '', true);
        }
    }

    /**
     * 获取会员系谱图
     * 如果是通过ajax请求，则返回json数据，
     * 否则返回视图
     * @return string
     */
    public function actionRelationGraph()
    {
        $request = Yii::$app->request;

        $searchModel = new RelationGraphForm();
        $searchModel->load($request->get());

        if ($searchModel->load($request->get()) && $request->isAjax) {
            if ($searchModel->validate()) {
                return $this->renderJson(['status' => 1, 'data' => $searchModel->getTreantData()]);
            } else {
                $this->error(json_encode($searchModel->errors));
            }
        }

        return $this->render('relation-graph', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 根据用户名搜索用户
     */
    public function actionSearch($user_name)
    {
        $rows = User::find()->select('id, username')->where('username like :username', [
            ':username' => "%" . $user_name . "%",
        ])->asArray()->all();
        return $this->renderJson($rows);
    }

    /**
     * 福利统计
     */
    public function actionWelfare()
    {

        return $this->render('welfare');
    }

    public function findModel($id)
    {
        $model = NormalUser::findOne($id);
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

    public function actionExists($username)
    {
        return $this->asJson(User::findOne(['username' => $username]) !== null);
    }

    public function actionValidateBroker($user_id)
    {
        // 验证此用户下是否已经有两个节点人

    }
}
