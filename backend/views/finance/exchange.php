<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:01
 */

use common\helpers\TransactionHelper;
use common\models\records\ExchangeLog;
use kartik\helpers\Html;
use common\core\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\ExchangeSearch */

$this->title = '申请记录';
$this->params['title_sub'] = '管理用户信息';

$columns = [
    [
        'header' => '会员账号',
        'value' => 'user.username'
    ],
    [
        'header' => '会员姓名',
        'value' => 'user.real_name'
    ],
    [
        'header' => '提现金额',
        'value' => 'amount'
    ],
    [
        'header' => '实发金额',
        'value' => function ($model) {
            return number_format($model->amount * (1 - TransactionHelper::RATIO_EXCHANGE_TAX), 2);
        }
    ],
    [
        'header' => '手机号',
        'value' => 'user.phone'
    ],
    [
        'header' => '开户银行',
        'value' => 'user.bank_name'
    ],
    [
        'header' => '银行账号',
        'value' => 'user.bank_account'
    ],
    [
        'header' => '开户名',
        'value' => 'user.bank_username'
    ],
    [
        'header' => '申请日期',
        'value' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
    ],
];
if ($searchModel->status == ExchangeLog::STATUS_CHECKING) {
    $columns[] = [
        'class' => 'yii\grid\ActionColumn',
        'header' => '操作',
        'template' => '{approve} {reject}',
        //'options' => ['width' => '200px;'],
        'buttons' => [
            'approve' => function ($url, $model, $key) {
                return Html::a('通过', ['exchange', 'eid' => $model->id, 'status' => ExchangeLog::STATUS_APPROVE], [
                    'title' => Yii::t('app', '通过申请'),
                    'class' => 'btn btn-xs ajax-post confirm',
                    'hide-data' => 'true'
                ]);
            },
            'reject' => function ($url, $model, $key) {
                return Html::a('拒绝', ['exchange', 'eid' => $model->id, 'status' => ExchangeLog::STATUS_REJECT], [
                    'title' => Yii::t('app', '拒绝申请'),
                    'class' => 'btn btn-xs ajax-post confirm',
                    'hide-data' => 'true'
                ]);
            },
        ],
    ];
}


?>

<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">管理信息</span>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_common_search', ['model' => $searchModel, 'hiddenFields' => ['status']]); ?> <!-- 条件搜索-->
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

<?php $this->beginBlock('test'); ?>
    jQuery(document).ready(function() {
    var nav_url = '/finance/exchange?status=' + urlParam('status');
    highlight_subnav(nav_url); //子导航高亮
    });
<?php $this->endBlock() ?>
    <!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>