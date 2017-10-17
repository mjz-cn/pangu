<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options'=>[
        //'class'=>"form-inline",
        'data-pjax' => true, //开启pjax搜索
    ]
]); ?>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'user_name')->textInput() ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'referrer_name')->textInput() ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'start_time')->textInput() ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'end_time')->textInput() ?>
    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
