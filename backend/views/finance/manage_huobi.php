<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:02
 */

use common\assets\BootstrapDialogAsset;
use common\core\ActiveForm;
use common\helpers\TransactionHelper;
use kartik\helpers\Html;
use kartik\widgets\Select2;
use common\core\GridView;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\ManageHuobiSearch */
/* @var $model backend\models\MangageHuobiForm */

BootstrapDialogAsset::register($this);

$this->title = '奖金详细';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$columns = [
    [
        'label' => '授权账号',
        'value' => 'admin.username'
    ],
    [
        'label' => '用户账号',
        'value' => 'user.username'
    ],
    [
        'label' => '操作金额',
        'value' => 'amount'
    ],
    [
        'label' => '备注',
        'format' => 'raw',
        'value' => 'desc'
    ],
    [
        'label' => '操作时间',
        'value' => function ($model) {
            return date('Y-m-d H:i', $model->create_time);
        },
    ]
];

?>


<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">管理信息</span>
        </div>
        <div class="actions">
            <div class="btn-group btn-group-devided">
                <?= Html::button('加减(电子币/奖金)', [
                    'class' => 'btn btn-primary',
                    "data-toggle" => "modal",
                    "data-target" => "#myModal"])
                ?>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_manage_huobi_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
        </div>
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                       id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider, // 列表数据
                    'columns' => $columns,
                ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">加减(电子币/奖金)</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'action' => [''],
                    'method' => 'post',
                    'options' => [
                        'class' => "form-aaa "
                    ]
                ]); ?>

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

                <?= $form->field($model, 'huobi_type')->radioList([
                    TransactionHelper::CURRENCY_DIANZIBI => '电子币', TransactionHelper::CURRENCY_JIANGJIN => '奖金'
                ]) ?>

                <?= $form->field($model, 'amount')->textInput(['type' => 'number'])->label('数值(正数代表增加,负数代表扣除)') ?>
                <?= $form->field($model, 'desc')->textarea() ?>

                <div class="form-actions">
                    <?= Html::submitButton('<i class="icon-ok"></i> 确定', ['class' => 'btn blue ajax-post', 'target-form' => 'form-aaa']) ?>
                    <?= Html::resetButton('取消', ['class' => 'btn']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>