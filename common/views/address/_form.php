<?php

use common\core\ActiveForm;
use common\helpers\ArrayHelper;
use common\models\records\Region;
use common\models\records\User;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\Address */
?>

<div class="address-form ">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $data = [];
    if ($model->user_id) {
        $data[$model->user_id] = User::getUsername($model->user_id);
    }
    echo $form->field($model, "user_id")->widget(Select2::classname(), [
        'data' => $data, // 填充username,
        'options' => ['placeholder' => '选择用户'],
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
    ]); ?>


    <div class="form-group">
        <div>
            <label>省 市 区</label>
            <span class="help-inline"></span>
        </div>
        <div class="col-md-2" style="padding-left:0px;">
            <?= \kartik\widgets\Select2::widget([
                'model' => $model,
                'attribute' => 'province',
                'data' => ArrayHelper::map(Region::find()->where(['parent_code' => 0])->asArray()->all(), 'code', 'fullname')
            ]); ?>
        </div>
        <div class="col-md-2">
            <?= \kartik\widgets\DepDrop::widget([
                'model' => $model,
                'attribute' => 'city',
                'options' => ['placeholder' => '选择'],
                'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    //'initialize' => true,
                    //'initDepends'=>['order-province'],
                    'depends' => [Html::getInputId($model, 'province')],
                    'url' => Url::to(['/public/region', 'sid' => $model['city']]),
                    'loadingText' => '加载中',
                ]
            ]); ?>
        </div>
        <div class="col-md-2">
            <?= \kartik\widgets\DepDrop::widget([
                'model' => $model,
                'attribute' => 'area',
                'options' => ['placeholder' => '选择'],
                'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'initialize' => true,
                    'initDepends' => [Html::getInputId($model, 'province')],
                    'depends' => [Html::getInputId($model, 'city')],
                    'url' => Url::to(['/public/region', 'sid' => $model['area']]),
                    'loadingText' => '加载中',
                ]
            ]); ?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'postcode')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
