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
        'class' => \common\core\CheckboxColumn::className(),
        'name'  => 'id',
        'options' => ['width' => '20px;'],
        'checkboxOptions' => function ($model, $key, $index, $column) {
            return ['value' => $key,'label'=>'<span></span>','labelOptions'=>['class' =>'mt-checkbox mt-checkbox-outline','style'=>'padding-left:19px;']];
        }
    ],
    [
        'header' => 'UID',
        'attribute' => 'id',
        'options' => ['width' => '50px;']
    ],
    [
        'header' => '用户名',
        'attribute' => 'username',
        'options' => ['width' => '150px;']
    ],
    [
        'header' => '邮箱',
        'attribute' => 'email',
        'options' => ['width' => '150px;']
    ],
    [
        'header' => '手机',
        'attribute' => 'phone',
        'options' => ['width' => '100px;']
    ],
    [
        'header' => '最后登录时间',
        'attribute' => 'last_login_time',
        'options' => ['width' => '150px;'],
        'format' => ['date', 'php:Y-m-d H:i']
    ],
    [
        'header' => '最后登录IP',
        'attribute' => 'last_login_ip',
        'options' => ['width' => '120px;'],
        'content' => function($model){
            return long2ip($model['last_login_ip']);
        }
    ],
    [
        'header' => '状态',
        'attribute' => 'status',
        'options' => ['width' => '50px;'],
        'content' => function($model) {
            $class = $model->status == $model::STATUS_ACTIVED ?
                'badge-success' : 'badge-important';
            return Html::tag('span',$model->statusText, ['class'=>'badge ' . $class]);
        }
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'header' => '操作',
        'template' => '{edit}',
        //'options' => ['width' => '200px;'],
        'buttons' => [
            'edit' => function ($url, $model, $key) {
                return Html::a('更新', ['edit','uid'=>$key], [
                    'title' => Yii::t('app', '更新'),
                    'class' => 'btn btn-xs purple'
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
        <div class="actions">
            <div class="btn-group btn-group-devided">
                <?=Html::a('添加',['add'],['class'=>'btn btn-default'])?>
                <?=Html::a('封禁',['delete'],['class'=>'btn red ajax-post confirm','target-form'=>'ids'])?>
                <?=Html::a('激活',['active'],['class'=>'btn green ajax-post confirm','target-form'=>'ids'])?>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options'=>['id'=>'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
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