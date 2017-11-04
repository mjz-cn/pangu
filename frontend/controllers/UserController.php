<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/1
 * Time: 下午1:49
 */

namespace frontend\controllers;

use backend\models\ActiveUserForm;
use common\models\ResetPasswordForm;
use common\models\search\NormalUserSearch;
use frontend\models\search\UserSearch;
use Yii;
use common\controllers\BaseController;
use common\models\NormalUser;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class UserController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'active-user' => ['POST'],
                    'check' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 推荐的会员列表/激活会员/已激活会员
     */
    public function actionIndex()
    {
        $this->setForward();

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 重置当前登录用户密码
     */
    public function actionResetPassword()
    {
        $form = new ResetPasswordForm();
        $form->user_id = Yii::$app->user->identity->getId();

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            if ($form->resetPassword()) {
                $this->success('重置密码成功', $this->getForward());
            } else {
                $this->error('重置密码失败');
            }
        }
        return $this->render('reset_password', [
            'model' => $form
        ]);
    }

    /**
     * 编辑资料
     */
    public function actionEdit()
    {
        $userModel = Yii::$app->user->identity;

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
     * 注册会员
     */
    public function actionAdd()
    {
        $userModel = new NormalUser();
        $userModel->loadDefaultValues();
        $userModel->referrer_id = Yii::$app->user->identity->getId();
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
     * 会员网络
     */
    public function actionUserTree()
    {

    }

    /**
     * 激活会员
     */
    public function actionActiveUser()
    {
        // TODO: 监测是否有权限激活用户

        $model = new ActiveUserForm();
        $model->setAttributes(Yii::$app->request->get());
        if ($model->validate() && $model->active()) {
            $this->success('激活成功', $this->getForward(), true);
        } else {
            $this->error(json_encode($model->errors), '', true);
        }
    }


    /**
     * @param $id
     * @return NormalUser
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = NormalUser::findOne($id);
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * 根据用户名搜索用户
     */
    public function actionSearch($user_name)
    {
        $rows = NormalUser::find()->select('id, username')->where('username like :username', [
            ':username' => "%" . $user_name . "%",
        ])->asArray()->all();
        return $this->renderJson($rows);
    }

    public function actionExists($username)
    {
        return $this->asJson(NormalUser::findOne(['username' => $username]) !== null);
    }
}