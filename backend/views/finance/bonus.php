<?php

use common\core\GridView;
use common\helpers\Constants;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\BonusSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '奖金详细';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$columns = [
    [
        'label' => '领路老师账号',
        'value' => 'broker.username',
    ],
    [
        'label' => '领路老师姓名',
        'value' => 'broker.real_name',
    ],
    [
        'label' => '会员账号',
        'value' => 'username'
    ],
    [
        'label' => '会员姓名',
        'value' => 'real_name',
    ],
    [
        'label' => '注册金额',
        'value' => 'reg_money',
    ],
    [
        'label' => '会员级别',
        'value' => 'levelText',
        'defaultValue' => '暂无'
    ],
    [
        'label' => '联系电话',
        'value' => 'phone',
    ],
    [
        'label' => '是否有效',
        'format' => 'raw',
        'value' => function ($model) {
            if ($model->is_shidan == Constants::NUMBER_TRUE) {
                return "有效";
            } else {
                return Html::tag("span", '无效', ['style' => 'color:red']);
            }
        }
    ],
    [
        'header' => '奖金累积',
        'value' => 'wallet.total_jiangjin',
        'defaultValue' => '0.00'
    ],
    [
        'header' => '奖金余额',
        'value' => 'wallet.jiangjin',
        'defaultValue' => '0.00'
    ],
    [
        'header' => '电子币累积',
        'value' => 'wallet.total_dianzi',
        'defaultValue' => '0.00'
    ],
    [
        'header' => '电子币余额',
        'value' => 'wallet.dianzi',
        'defaultValue' => '0.00'
    ],
    [
        'label' => '开通日期',
        'value' => function($model) {return date('Y-m-d H:i', $model->create_time);},
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'header' => '操作',
        'template' => '{view}',
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return Html::a('查看明细', ['index',
                    'detail_type' => \backend\models\search\FinanceSearch::DETAIL_TYPE_DAILY,
                    'user_id' => $model['id']
                ], [
                    'title' => Yii::t('app', '查看明细'),
                    'class' => 'btn btn-xs'
                ]);
            },
        ],
    ],
];
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
            <?php echo $this->render('_bonus_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
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
