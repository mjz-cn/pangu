<?php

use common\core\ActiveForm;
use common\helpers\ArrayHelper;
use common\models\records\Region;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\records\Address */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-form ">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <div>
            <label>省 市 区</label>
            <span class="help-inline"></span>
        </div>
        <div class="col-md-2" style="padding-left:0px;">
            <?=\kartik\widgets\Select2::widget([
                'model' => $model,
                'attribute' => 'province',
                'data' => ArrayHelper::map(Region::find()->where(['parent_code'=>0])->asArray()->all(), 'code', 'fullname')
            ]);?>
        </div>
        <div class="col-md-2">
            <?=\kartik\widgets\DepDrop::widget([
                'model' => $model,
                'attribute' => 'city',
                'options' => ['placeholder' => '选择'],
                'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    //'initialize' => true,
                    //'initDepends'=>['order-province'],
                    'depends'=>[Html::getInputId($model, 'province')],
                    'url' => Url::to(['/public/region','sid'=>$model['city']]),
                    'loadingText' => '加载中',
                ]
            ]);?>
        </div>
        <div class="col-md-2">
            <?=\kartik\widgets\DepDrop::widget([
                'model' => $model,
                'attribute' => 'area',
                'options' => ['placeholder' => '选择'],
                'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'initialize' => true,
                    'initDepends'=>[Html::getInputId($model, 'province')],
                    'depends'=>[Html::getInputId($model, 'city')],
                    'url' => Url::to(['/public/region','sid'=>$model['area']]),
                    'loadingText' => '加载中',
                ]
            ]);?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'postcode')->textInput() ?>

    <?= $form->errorSummary($model) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
