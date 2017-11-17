<?php

use common\Helpers\Constants;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\search\UserSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
//$this->title = '用户管理';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$columns = [
    [
        'label' => '领路老师账号',
        'value' => 'broker.username',
        'defaultValue' => '-'
    ],
    [
        'label' => '领路老师姓名',
        'value' => 'broker.real_name',
        'defaultValue' => '-'
    ],
    [
        'header' => '会员账号',
        'attribute' => 'username',
    ],
    [
        'header' => '会员姓名',
        'attribute' => 'real_name',
    ],
    [
        'header' => '会员级别',
        'attribute' => 'levelText',
    ],
    [
        'header' => '电话',
        'attribute' => 'phone',
    ],
    [
        'header' => '加盟商层级',
        'attribute' => 'depth',
    ],
    [
        'header' => '区域',
        'attribute' => 'detailAddress'
    ],
    [
        'header' => '注册时间',
        'attribute' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
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
                <?= Html::a('添加', ['add'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
        </div>
        <div class="table-container">
            <?= \common\core\GridView::widget([
                'dataProvider' => $dataProvider, // 列表数据
                'columns' => $columns
            ]); ?>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>