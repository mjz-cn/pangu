<?php

use common\models\records\User;
use kartik\helpers\Html;
use kartik\widgets\Select2;
use common\core\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\records\User */
/* @var $userInfoModel common\models\records\NormalUserInfo */
/* @var $form ActiveForm */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '添加用户';
$this->params['title_sub'] = '添加前台用户';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">

    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <i class="icon-settings font-red-sunglo"></i>
                <span class="caption-subject bold uppercase"> 内容信息</span>
            </div>
        </div>
        <hr>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => "user-add-form",
                'autocomplete' => "off"
            ]
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'username')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '用户名',
                    'autocomplete' => "off"

                ])->label('用户名') ?>

                <?= $form->field($model, 'password')->iconTextInput([
                    'class' => 'form-control c-md-10',
//            'type' => 'password',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-lock',
                    'placeholder' => '密码',
                    'autocomplete' => "off"

                ])->label('密码') ?>

                <?= $form->field($model, 'email')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-envelope',
                    'placeholder' => '邮箱'
                ])->label('邮箱') ?>

                <?= $form->field($model, 'phone')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'fa fa-phone',
                    'placeholder' => '电话'
                ])->label('电话') ?>

                <?= $form->field($model, 'wechat')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'fa fa-phone',
                    'placeholder' => '微信账号'
                ])->label('微信账号') ?>

                <?= $form->field($model, 'qq')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'fa fa-phone',
                    'placeholder' => 'QQ'
                ])->label('QQ') ?>

                <?php
                $data = [];
                if ($model->broker_id) {
                    $data[$model->broker_id] = User::getUsername($model->broker_id);
                }
                echo $form->field($model, "broker_id")->widget(Select2::classname(), [
                    'data' => $data, // 填充username,
                    'options' => ['placeholder' => '选择领路人'],
                    'addon' => [
                        'prepend' => [
                            'content' => Html::icon('user', [], 'icon-')
                        ],
                    ],
                    'initValueText' => '',
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'dataType' => 'json',
                        'ajax' => [
                            'url' => \yii\helpers\Url::toRoute('/user/search'),
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {user_name:params.term}; }'),
                            'processResults' => new JsExpression('function(data, params) {return {results: data};}'),
                            'cache' => true
                        ],
                        'templateResult' => new JsExpression('function(user) { return user.username; }'),
                        'templateSelection' => new JsExpression('function (user) { 
                    if (user.username) {return user.username}
                    return user.text; }
                '),
                    ],
                ], [
                    'class' => 'c-md-10',
                ]); ?>

            </div>

            <div class="col-md-6">
                <?php
                $data = [];
                if ($model->baodan_id) {
                    $data[$model->baodan_id] = \common\models\records\Baodan::getName($model->baodan_id);
                }
                echo $form->field($model, "baodan_id")->widget(Select2::classname(), [
                    'data' => $data, // 填充username,
                    'options' => ['placeholder' => '选择报单中心'],
                    'addon' => [
                        'prepend' => [
                            'content' => Html::icon('user', [], 'icon-')
                        ],
                    ],
                    'initValueText' => '',
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'dataType' => 'json',
                        'ajax' => [
                            'url' => \yii\helpers\Url::toRoute('/baodan/search'),
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {name:params.term}; }'),
                            'processResults' => new JsExpression('function(data, params) {return {results: data};}'),
                            'cache' => true
                        ],
                        'templateResult' => new JsExpression('function(baodan) { return baodan.name; }'),
                        'templateSelection' => new JsExpression('function (baodan) { 
                            if (baodan.name) {return baodan.name}
                            return baodan.text; }
                        '),
                    ],
                ], [
                    'class' => 'c-md-10',
                ]); ?>

                <?= $form->field($model, 'real_name')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '真实姓名'
                ]) ?>

                <?= $form->field($model, 'gender')->radioList(['0' => '女', '1' => '男']) ?>

                <?= $form->field($model, 'card_id')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'type' => 'number',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '身份证号'
                ]) ?>

                <?= $form->field($model, 'bank_account')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'type' => 'number',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '银行账户'
                ]) ?>

                <?= $form->field($model, 'bank_name')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '开户行名称'
                ]) ?>

                <?= $form->field($model, 'bank_username')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '银行帐户姓名'
                ]) ?>

            </div>
        </div>
        <?= $form->errorSummary($model) ?>
        <div class="form-actions row">

            <?= Html::submitButton('确定', ['class' => 'btn blue']) ?>
            <?= Html::a('取消', ['site/index'], ['class' => 'btn btn-default']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <!-- END FORM-->
    </div>


</div>
