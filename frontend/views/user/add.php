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
                'class' => "form-aaa "
            ]
        ]); ?>

        <?= $form->field($model, 'username')->iconTextInput([
            'class' => 'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => 'username'
        ])->label('用户名') ?>

        <div class="form-group">
            <label>密码</label>
            <div class="">
                <div class="input-icon left">
                    <i class="icon-lock"></i>
                    <input type="password" class="form-control c-md-3"
                           name=<?= '"' . $model->formName() . '[password]"' ?> placeholder="密码不变请留空" />
                </div>
            </div>
        </div>

        <?= $form->field($model, 'email')->iconTextInput([
            'class' => 'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-envelope',
            'placeholder' => 'Email Address'
        ])->label('邮箱') ?>

        <?= $form->field($model, 'phone')->iconTextInput([
            'class' => 'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'fa fa-phone',
            'placeholder' => 'Phone'
        ])->label('电话') ?>

        <?php
        if ($model->isNewRecord) {
            $model->status = $model::STATUS_NOT_ACTIVED;
        }
        echo $form->field($model, 'status')->radioList($model::getStatusArr())->label('用户状态')
        ?>

        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <i class="icon-settings font-red-sunglo"></i>
                <span class="caption-subject bold uppercase"> 内容信息</span>
            </div>
        </div>
        <hr>

        <?php
        $data = [];
        if ($model->broker_id) {
            $data[$model->broker_id] = User::getUsername($model->broker_id);
        }
        echo $form->field($model, "broker_id")->widget(Select2::classname(), [
            'data' => $data, // 填充username,
            'options' => ['placeholder' => '选择接点人'],
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
            'class' => 'c-md-3',
        ]); ?>

        <?php
        $data = [];
        if ($model->referrer_id) {
            $data[$model->referrer_id] = User::getUsername($model->referrer_id);
        }
        echo $form->field($model, "referrer_id")->widget(Select2::classname(), [
            'data' => $data, // 填充username,
            'class' => 'form-control c-md-2',
            'options' => ['placeholder' => '选择推荐人'],
            'addon' => [
                'prepend' => [
                    'content' => Html::icon('user', [], 'icon-')
                ],
            ],
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
            'class' => 'c-md-3',
        ]); ?>

        <?= $form->field($model, 'real_name')->iconTextInput([
            'class' => 'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => 'real_name'
        ]) ?>

        <?= $form->field($model, 'gender')->radioList(['0' => '女', '1' => '男']) ?>

        <?= $form->field($model, 'card_id')->iconTextInput([
            'class' => 'form-control c-md-3',
            'type' => 'number',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => '身份证号'
        ]) ?>

        <?= $form->field($model, 'bank_account')->iconTextInput([
            'class' => 'form-control c-md-3',
            'type' => 'number',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => '银行账户'
        ]) ?>

        <?= $form->field($model, 'bank_name')->iconTextInput([
            'class' => 'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => '开户行名称'
        ]) ?>

        <?= $form->field($model, 'bank_username')->iconTextInput([
            'class' => 'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => '银行帐户姓名'
        ]) ?>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 确定', ['class' => 'btn blue ajax-post', 'target-form' => 'form-aaa']) ?>
            <?= Html::button('取消', ['class' => 'btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <!-- END FORM-->
    </div>


</div>
