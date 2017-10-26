<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $model common\models\NormalUser */
/* @var $dataProvider yii\data\ActiveDataProvider  */
/* @var $searchModel common\models\search\NormalUserSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '用户管理';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

/* 加载页面级别资源 */
\backend\assets\TablesAsset::register($this);

$columns = [
    [
        'label' => '推荐人账号',
        'value' => function ($model, $key, $index, $column) {
            $referrer = $model->referrer;
            if (empty($referrer)) {
                return '-';
            } else {
                return $referrer->username;
            }
        },
    ],
    [
        'label' => '会员账号',
        'value' => 'username'
    ],
    [
        'label' => '真实姓名',
        'value' => 'real_name',
    ],
    [
        'label' => '会员姓名',
        'value' => 'real_name',
    ],
    [
        'label' => '会员级别',
        'value' => 'level',
    ],
    [
        'label' => '注册金额',
        'value' => 'real_name',
    ],
    [
        'label' => '开通日期',
        'value' => function($model) {return date('Y-m-d H:i', $model->create_time);},
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
        <?php \yii\widgets\Pjax::begin(['options'=>['id'=>'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_members_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
        </div>
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider, // 列表数据
                    'options' => ['class' => 'grid-view table-scrollable'],
                    /* 表格配置 */
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer'],
                    /* 重新排版 摘要、表格、分页 */
                    'layout' => '{items}<div class=""><div class="col-md-5 col-sm-5">{summary}</div><div class="col-md-7 col-sm-7">
                    <div class="dataTables_paginate paging_bootstrap_full_number" style="text-align:right;">{pager}</div></div></div>',
                    /* 配置摘要 */
                    'summaryOptions' => ['class' => 'pagination'],
                    /* 配置分页样式 */
                    'pager' => [
                        'options' => ['class'=>'pagination','style'=>'visibility: visible;'],
                        'nextPageLabel' => '下一页',
                        'prevPageLabel' => '上一页',
                        'firstPageLabel' => '第一页',
                        'lastPageLabel' => '最后页'
                    ],
                    /* 定义列表格式 */
                    'columns' => $columns,
                ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>

<?php $this->beginBlock('test'); ?>
    jQuery(document).ready(function() {
    var nav_url = '/baodan/index?status=2';
    highlight_subnav(nav_url); //子导航高亮
    });
<?php $this->endBlock() ?>
    <!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>