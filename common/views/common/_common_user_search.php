<?php

use common\core\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $searchModel \yii\base\Model */
/* @var $this yii\web\View */
/* @var $form common\core\ActiveForm */
/* @var $shownFields   array   要展示在在表单中的field */

?>

<?php $form = ActiveForm::begin([
    'action' => [Yii::$app->request->pathInfo],
    'method' => 'get',
    'options' => [
        //'class'=>"form-inline",
        'data-pjax' => true, //开启pjax搜索
    ]
]); ?>
<div class="row">
    <div style="display:none;">
        <?php
        if (!empty($hiddenFields)) {
            foreach ($hiddenFields as $hiddenField) {
                echo $form->field($model, $hiddenField)->hiddenInput();
            }
        }
        ?>
    </div>
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
            ]
        ]); ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'start_time')->widget(DatePicker::className(), [
                'attribute' => 'start_time',
                'attribute2' => 'end_time',
                'options' => ['placeholder' => '开始日期'],
                'options2' => ['placeholder' => '结束日期'],
                'separator' => '至',
                'type' => DatePicker::TYPE_RANGE,
                'form' => $form,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                ]
            ]
        )->label('时间范围'); ?>
    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('重置', [Yii::$app->request->pathInfo],['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
