<?php

use common\core\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider  */
/* @var $searchModel backend\models\search\BonusSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '奖金详细';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$columns = [

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
            <?php echo $this->render('_bonus_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
        </div>
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden" id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider, // 列表数据
                    //'filterModel' => $searchModel, // 搜索模型
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
