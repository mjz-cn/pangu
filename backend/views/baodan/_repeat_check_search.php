<?php

use common\core\ActiveForm;
use common\models\records\RechargeLog;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\search\RechargeLogSearch */
/* @var $form common\core\ActiveForm */
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

    <div class="col-md-4">
        <?php echo '<label class="control-label">时间范围</label>';
        echo DatePicker::widget([
            'model' => $model,
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
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'baodan_status', ['inputOptions' => ['class' => 'form-control']])->selectList([
            '' => '全部',
            RechargeLog::STATUS_CHECKING => '审核中',
            RechargeLog::STATUS_APPROVE => '通过',
            RechargeLog::STATUS_REJECT => '拒绝',
        ]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'status', ['inputOptions' => ['class' => 'form-control']])->selectList([
            '' => '全部',
            RechargeLog::STATUS_CHECKING => '审核中',
            RechargeLog::STATUS_APPROVE => '通过',
            RechargeLog::STATUS_REJECT => '拒绝',
        ]) ?>
    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn blue']) ?>
            <?= Html::a('重置', [Yii::$app->request->pathInfo],['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
