<?php

use common\Helpers\Constants;
use common\models\NormalUser;
use yii\helpers\Html;

/* @var $model common\models\NormalUser */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel common\models\search\NormalUserSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '用户管理';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

/* 加载页面级别资源 */
\backend\assets\TablesAsset::register($this);

$columns = [
    [
        'header' => '注册时间',
        'value' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
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
        'header' => '会员当前级别',
        'attribute' => 'levelText',
    ],
    [
        'header' => '电话',
        'value' => 'phone',
        'defaultValue' => '-'
    ],
    [
        'header' => '区域',
        'attribute' => 'detailAddress'
    ],
    [
        'header' => '是否有效',
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
        'header' => '一级加盟商',
        'value' => 'child.level1'
    ],
    [
        'header' => '二级加盟商',
        'value' => 'child.level2'
    ],
    [
        'header' => '三级加盟商',
        'value' => 'child.level3'
    ],
    [
        'header' => '加盟商总人数',
        'value' => 'child.total'
    ],
    [
        'header' => '当前级别',
        'value' => 'levelText'
    ],
    [
        'header' => '建议级别',
        'value' => function ($model) {
            $total = $model->child['total'];
            return NormalUser::LEVEL_ARR[NormalUser::countLevel($total)];
        }
    ],
    [
        'header' => '审核',
        'format' => 'raw',
        'value' => function ($model) {
            $form = Html::beginForm(['level-check'], 'post');
            $form .= Html::dropDownList('level', $model->level, NormalUser::LEVEL_ARR);
            $form .= Html::hiddenInput('uid', $model->id);
            $form .= Html::submitButton('确认', ['class' => 'btn btn-link btn-xs']);
            $form .= Html::endForm();
            return $form;
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