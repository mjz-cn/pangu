<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\AddressSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, "user_id")->widget(Select2::classname(), [
                'data' => [],
                'options' => ['placeholder' => '选择用户'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'dataType' => 'json',
                    'ajax' => [
                        'url' => \yii\helpers\Url::toRoute('/user/search'),
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {user_name:params.term}; }'),
                        'processResults' => new JsExpression('function(data, params) {return {results: data};}'),
                    ],
                    'templateResult' => new JsExpression('function(user) { return user.username; }'),
                    'templateSelection' => new JsExpression('function (user) { return user.username; }'),
                ],
            ]); ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'phone') ?>
        </div>

        <div class="col-md-2">
            <div class="form-group" style="margin-top: 24px;">
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('重置', [Yii::$app->request->pathInfo],['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>