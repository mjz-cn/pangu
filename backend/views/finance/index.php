<?php

use common\core\GridView;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\FinanceSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '奖金详细';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

if ($searchModel->detail_type == $searchModel::DETAIL_TYPE_ALL) {
    $columns = [
        [
            'label' => '结算日期',
            'value' => 'date'
        ],
        [
            'label' => '一级奖金',
            'value' => 'bd_revenue_1'
        ],
        [
            'label' => '二级奖金',
            'value' => 'bd_revenue_2'
        ],
        [
            'label' => '三级奖金',
            'value' => 'bd_revenue_3'
        ],
        [
            'label' => '实发奖金',
            'value' => function ($model) {
                $amount = 0;
                foreach ($model as $key => $value) {
                    if ($key != 'date') {
                        $amount += $value;
                    }
                }
                return $amount;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('查看明细', ['index',
                        'detail_type' => \backend\models\search\FinanceSearch::DETAIL_TYPE_DAILY,
                        'start_time' => $model['date'],
                        'end_time' => $model['date'],
                    ], [
                        'title' => Yii::t('app', '奖金明细'),
                        'class' => 'btn btn-xs'
                    ]);
                },
            ],
        ],
    ];
} elseif ($searchModel->detail_type == $searchModel::DETAIL_TYPE_DAILY) {
    $columns = [
        [
            'label' => '结算日期',
            'value' => 'date'
        ],
        [
            'label' => '用户账号',
            'value' => 'username'
        ],
        [
            'label' => '会员姓名',
            'value' => 'real_name'
        ],
        [
            'label' => '一级奖金',
            'value' => 'bd_revenue_1'
        ],
        [
            'label' => '二级奖金',
            'value' => 'bd_revenue_2'
        ],
        [
            'label' => '三级奖金',
            'value' => 'bd_revenue_3'
        ],
        [
            'label' => '实发奖金',
            'value' => function ($model) {
                $amount = 0;
                foreach ($model as $key => $value) {
                    if (strripos($key, 'revenue') !== false || strripos($key, 'tax') !== false) {
                        $amount += $value;
                    }
                }
                return $amount;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('查看明细', ['index',
                        'detail_type' => \backend\models\search\FinanceSearch::DETAIL_TYPE_DAILY_USER,
                        'start_time' => $model['date'],
                        'end_time' => $model['date'],
                        'user_id' => $model['user_id']
                    ], [
                        'title' => Yii::t('app', '奖金明细'),
                        'class' => 'btn btn-xs'
                    ]);
                },
            ],
        ],
    ];
} elseif ($searchModel->detail_type == $searchModel::DETAIL_TYPE_DAILY_USER) {
    $columns = [
        [
            'label' => '用户账号',
            'value' => 'user.username'
        ],
        [
            'label' => '会员姓名',
            'value' => 'user.real_name'
        ],
        [
            'header' => '金额说明',
            'format' => 'raw',
            'value' => 'desc',
        ],
        [
            'header' => '金额',
            'value' => 'amount',
        ],
        [
            'header' => '结算日期',
            'value' => 'create_time',
            'format' => ['date', 'php:Y-m-d H:i']
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
        <div>
            <?php echo $this->render('_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
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
    </div>
</div>
