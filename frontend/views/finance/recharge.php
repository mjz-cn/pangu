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

$this->title = '重复报单';
$this->params['title_sub'] = '管理用户信息';

$columns = [
    [
        'header' => '报单金额',
        'value' => function ($model) {
            return number_format($model->amount);
        }
    ],
    [
        'header' => '申请日期',
        'value' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
    ],
    [
        'header' => '上级审核状态',
        'value' => 'baodanStatusText'
    ],
    [
        'header' => '公司财务审核状态',
        'value' => 'statusText'
    ]
];

?>

<div class="panel  panel-default">
    <div class="panel-heading" style="margin-bottom: 10px;">
        <i class="icon-settings font-dark"></i>
        <span class="">重复报单</span>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-4">
                <?php $form = ActiveForm::begin([
                    'action' => ['recharge'],
                    'method' => 'post',
                    'layout' => 'inline',
                    'options' => [
                        'data-pjax' => true, //开启pjax搜索
                        'class' => "form-aaa"
                    ]
                ]); ?>
                <?= $form->field($model, 'amount', ['template' => "{label}\n<div class=\"input-group\">{input}<span class=\"input-group-addon\">万</span></div>\n{hint}\n{error}",
                ])->textInput([
                    'type' => 'number',
                    'class' => 'form-control input-sm'
                ])->label('报单金额:') ?>
                <?= Html::submitButton('确定', [
                    'class' => 'btn btn-xs blue',
                    'style' => 'margin-bottom:5px',
                    'target-form' => 'form-aaa',
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="text-danger">
                报单金额单位为：万
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="icon-settings font-dark"></i> 重复报单记录
    </div>
    <div class="panel-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_common_search', ['model' => $searchModel]); ?>
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
