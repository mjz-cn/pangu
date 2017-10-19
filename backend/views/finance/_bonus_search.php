<?php

use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\BonusSearch */
/* @var $form common\core\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        //'class'=>"form-inline",
        'data-pjax' => true, //开启pjax搜索
    ]
]); ?>
<div class="row">
    <div class="col-md-2" style="padding-left:0;">
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
        ]);?>
    </div>
    <div class="col-md-2" style="padding-left:0;">
        <?= $form->field($model, "referrer_id")->widget(Select2::classname(), [
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
        ]);?>
    </div>
    <div class="col-md-4">

        <?php echo '<label class="control-label">时间范围</label>';
         echo DatePicker::widget([
            'model' => $model,
            'attribute' => 'start_time',
            'attribute2' => 'end_time',
            'options' => ['placeholder' => 'Start date'],
            'options2' => ['placeholder' => 'End date'],
            'separator' => '至',
            'type' => DatePicker::TYPE_RANGE,
            'form' => $form,
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose' => true,
            ]
        ]); ?>

    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
