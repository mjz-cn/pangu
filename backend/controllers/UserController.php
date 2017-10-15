<?php

namespace backend\controllers;

use backend\models\RelationGraphForm;
use common\models\NormalUser;
use common\models\records\NormalUserInfo;
use common\models\records\User;
use common\models\search\NormalUserSearch;
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
     * ---------------------------------------
     * 添加
     * ---------------------------------------
     */
    public function actionAdd()
    {
        $userModel = new NormalUser();
        $userInfoModel = new NormalUserInfo();

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

            $result = false;
            /* 保存用户数据到数据库 */
            if ($userInfoModel->load(Yii::$app->request->post()) && $userModel->save()) {
                $userInfoModel->user_id = $userModel->id;

                if ($userInfoModel->save()) {
                    $result = true;
                    $this->success('操作成功', $this->getForward());
                } else {
                    $userModel->delete();
                }
            }
            if (!$result) {
                $errors = array_merge([], $userModel->errors, $userInfoModel->errors);
                $this->error(json_encode($errors));
            }
        }

        return $this->render('edit', [
            'model' => $userModel,
            'userInfoModel' => $userInfoModel
        ]);
    }

    /**
     * ---------------------------------------
     * 编辑
     * ---------------------------------------
     */
    public function actionEdit($uid)
    {
        $userModel = $this->findModel($uid);
        $userInfoModel = $userModel->normalUserInfo;
        if (empty($userInfoModel)) {
            $userInfoModel = new NormalUserInfo();
        }

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

            $result = false;
            /* 保存用户数据到数据库 */
            if ($userInfoModel->load(Yii::$app->request->post()) && $userModel->save()) {
                $userInfoModel->user_id = $userModel->id;

                if ($userInfoModel->save()) {
                    $result = true;
                    $this->success('操作成功', $this->getForward());
                }
            }
            if (!$result) {
                var_dump($userModel->errors);
                var_dump($userInfoModel->errors);
                $this->error('操作错误');
            }

        }

        return $this->render('edit', [
            'model' => $userModel,
            'userInfoModel' => $userInfoModel,
        ]);
    }

    /**
     * ---------------------------------------
     * 删除
     * ---------------------------------------
     */
    public function actionDelete()
    {
        $ids = Yii::$app->request->param('id', 0);
        $ids = array_unique((array)$ids);

        if (empty($ids)) {
            $this->error('请选择要操作的用户!');
        }

        // 也要删除用此用户相关的信息
        // 由于与此用户关联的信息过多，目前只更新用户的状态为封禁状态
        if (NormalUser::banUsers($ids) > 0) {
            $this->success('封禁成功', $this->getForward());
        } else {
            $this->error('封禁失败！');
        }
    }

    /**
     * 激活用户
     */
    public function actionActive() {
        $ids = Yii::$app->request->param('id', 0);
        $ids = array_unique((array)$ids);

        if (empty($ids)) {
            $this->error('请选择要操作的数据!');
        }

        if (NormalUser::activeUsers($ids) > 0) {
            $this->success('激活成功', $this->getForward());
        } else {
            $this->error('激活失败！');
        }
    }

    /**
     * 获取会员系谱图
     * 如果是通过ajax请求，则返回json数据，
     * 否则返回视图
     * @return string
     */
    public function actionRelationGraph() {
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

    public function findModel($id) {
        $model = NormalUser::findOne($id);
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
