<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:01
 */

use common\core\ActiveForm;
use common\helpers\TransactionHelper;
use common\models\records\ExchangeLog;
use kartik\helpers\Html;
use common\core\GridView;

/* @var $this yii\web\View */
/* @var $model ExchangeLog */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\ExchangeSearch */

$this->title = '申请记录';
$this->params['title_sub'] = '管理用户信息';

$columns = [
    [
        'header' => '提现金额',
        'value' => 'amount'
    ],
    [
        'header' => '提现费',
        'value' => function ($model) {
            return number_format($model->amount * TransactionHelper::RATIO_EXCHANGE_TAX, 2);
        }
    ],
    [
        'header' => '实发金额',
        'value' => function ($model) {
            return number_format($model->amount * (1 - TransactionHelper::RATIO_EXCHANGE_TAX), 2);
        }
    ],
    [
        'header' => '申请日期',
        'value' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
    ],
    [
        'header' => '状态',
        'value' => 'statusText'
    ]
];


?>

<div class="panel  panel-default">
    <div class="panel-heading" style="margin-bottom: 10px;">
        <i class="icon-settings font-dark"></i>
        <span class="">我要提现</span>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-2">我的余额：<?= $balance ?></div>
            <div class="col-md-3">
                <?php $form = ActiveForm::begin([
                    'action' => ['exchange'],
                    'method' => 'post',
                    'layout' => 'inline',
                    'options' => [
                        'data-pjax' => true, //开启pjax搜索
                        'class' => "form-aaa"
                    ]
                ]); ?>
                <?= $form->field($model, 'amount', ['template' => "{label}\n{input}\n{hint}\n{error}"])->textInput([
                    'type' => 'number',
                    'class' => 'form-control input-sm'
                ]) ?>
                <?= Html::submitButton('确定', [
                    'class' => 'btn btn-xs blue',
                    'target-form' => 'form-aaa',
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="text-danger">
                注：提现扣 3% 的手续费; 每天只能提现一次！
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="icon-settings font-dark"></i> 提现记录
    </div>
    <div class="panel-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_common_search', ['model' => $searchModel, 'hiddenFields' => ['status']]); ?>
            <!-- 条件搜索-->
        </div>
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                       id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider, // 列表数据
                    'columns' => $columns
                ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
