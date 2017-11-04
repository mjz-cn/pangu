<?php

use common\core\GridView;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\FinanceSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '奖金详细';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

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
        'label' => '推荐奖',
        'value' => 'referrer_revenue'
    ],
    [
        'label' => '运营奖',
        'value' => 'bd_revenue'
    ],
    [
        'label' => '拓展奖',
        'value' => 'bd_1_revenue'
    ],
    [
        'label' => '月底薪',
        'defaultValue' => '0'
    ],
    [
        'label' => '报单佣金',
        'defaultValue' => '0'
    ],
    [
        'label' => '扣税',
        'value' => 'manage_tax'
    ],
    [
        'label' => '重复消费',
        'value' => 'chongxiao_tax'
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
    ]
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
