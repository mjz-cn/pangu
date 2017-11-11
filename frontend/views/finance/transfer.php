<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:00
 */

use common\core\ActiveForm;
use common\core\GridView;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\search\TransferSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '奖金详细';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$columns = [
    [
        'header' => '对方账号',
        'value' => 'fromUser.username'
    ],
    [
        'header' => '姓名',
        'value' => 'fromUser.real_name'
    ],
    [
      'header' => '转账金额',
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
        <span class="">我要转账</span>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-2">电子币余额：<?= $dianziBalance ?></div>
            <div class="col-md-9">
                <?php $form = ActiveForm::begin([
                    'action' => ['transfer'],
                    'method' => 'post',
//                    'layout' => 'inline',
                    'options' => [
                        'data-pjax' => true, //开启pjax搜索
                        'class' => "form-aaa row"
                    ]
                ]); ?>
                <div class="col-md-4">
                    <?= $form->field($transferModel, "user_id")->widget(Select2::classname(), [
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
                    ], ['class' => '']); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($transferModel, 'amount', ['template' => "{label}\n{input}\n{hint}\n{error}"])->textInput([
                        'type' => 'number',
                        'class' => 'form-control input-sm'
                    ]) ?>
                </div>
                <?= Html::submitButton('确定', [
                    'class' => 'btn btn-xs blue',
                    'target-form' => 'form-aaa',
                    'style' => 'margin-top:25px'
                ]) ?>
                <?php ActiveForm::end(); ?>

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
