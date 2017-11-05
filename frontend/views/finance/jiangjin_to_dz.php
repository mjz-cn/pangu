<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:00
 */

use common\core\ActiveForm;
use common\core\GridView;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\search\TransferSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '电子币转换';
$this->params['title_sub'] = '财务中心';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$columns = [
    [
        'header' => '奖金减少',
        'value' => 'amount'
    ],
    [
        'header' => '电子币增加',
        'value' => 'amount'
    ],
    [
        'header' => '转账日期',
        'value' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
    ],
];
?>

<div class="panel  panel-default">
    <div class="panel-heading" style="margin-bottom: 10px;">
        <i class="icon-settings font-dark"></i>
        <span class="">我要转换电子币</span>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-3">奖金余额：<?= $jiangjinBalance ?>
                <span style="margin-left: 35px"></span>电子币余额：<?= $dianzibiBalance ?></div>
            <div class="col-md-4">
                <?php $form = ActiveForm::begin([
                    'action' => ['bonus-to-dianzibi'],
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
                ])->label('转换金额:') ?>
                <?= Html::submitButton('确定', [
                    'class' => 'btn btn-xs blue',
                    'target-form' => 'form-aaa',
                    'style' => " margin-bottom: 7px;"
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="text-danger">
                转换比例：1：1
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="icon-settings font-dark"></i> 转换记录
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
