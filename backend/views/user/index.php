<?php

use common\Helpers\Constants;
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

if ($searchModel->is_actived) {
    $columns = [
        [
            'label' => '推荐人账号',
            'value' => 'referrer.username',
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
            'header' => '实际金额',
            'attribute' => 'reg_money'
        ],
        [
            'header' => '接点人账号',
            'value' => 'broker.username',
            'defaultValue' => '-'
        ],
        [
            'header' => '电话',
            'value' => 'phone',
            'defaultValue' => '-'
        ],
        [
            'header' => '是否实单',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->is_shidan == Constants::NUMBER_TRUE) {
                    return "实单";
                } else {
                    return Html::tag("span", '空单', ['style' => 'color:red']);
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
            'header' => '电子币余额',
            'value' => 'wallet.dianzi',
            'defaultValue' => '0.00'
        ],
        [
            'header' => '重消余额',
            'value' => 'wallet.chongxiao',
            'defaultValue' => '0.00'
        ],
        [
            'header' => '管理级别',
            'defaultValue' => '暂无',
        ],
        [
            'header' => '谁开通',
            'value' => function ($model, $key, $index, $column) {
                return "管理员";
            }
        ],
        [
            'header' => '注册时间',
            'attribute' => 'create_time',
            'format' => ['date', 'php:Y-m-d H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{edit} {ban}',
            //'options' => ['width' => '200px;'],
            'buttons' => [
                'edit' => function ($url, $model, $key) {
                    return Html::a('更新', ['edit', 'id' => $key], [
                        'title' => Yii::t('app', '更新'),
                        'class' => 'btn btn-xs btn-default'
                    ]);
                },
                'ban' => function ($url, $model, $key) {
                    $text = "解封";
                    $css = "btn-primary";
                    if ($model->is_baned == Constants::NUMBER_FALSE) {
                        $text = "冻结";
                        $css = "btn-danger";
                    }
                    return Html::a($text, ['check-ban', 'id' => $model->id], [
                            'class' => 'btn btn-xs ajax-post ' . $css,
                            'hide-data' => 'true',
                            'data-confirm' => sprintf("您确定要%s此用户吗？", $text)
                        ]
                    );
                }
            ],
        ],
    ];
} else {
    $columns = [
        [
            'label' => '推荐人账号',
            'value' => 'referrer.username',
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
            'header' => '注册时间',
            'attribute' => 'create_time',
            'format' => ['date', 'php:Y-m-d H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{edit} {b1} {b2} {delete}',
            //'options' => ['width' => '200px;'],
            'buttons' => [
                'edit' => function ($url, $model, $key) {
                    return Html::a('更新', ['edit', 'id' => $key], [
                        'title' => Yii::t('app', '更新'),
                        'class' => 'btn btn-xs btn-default'
                    ]);
                },
                'b1' => function ($url, $model, $key) {
                    return Html::a('开通空单', ['active', 'user_id' => $model->id, 'active_status' => 0], [
                        'class' => 'btn btn-xs btn-info ajax-post',
                        'hide-data' => 'true',
                        'data-confirm' => "您确定要为此用户开通空单吗？"
                    ]);
                },
                'b2' => function ($url, $model, $key) {
                    return Html::a('开通实单', ['active', 'user_id' => $model->id, 'active_status' => 1], [
                        'class' => 'btn btn-xs btn-warning ajax-post',
                        'hide-data' => 'true',
                        'data-confirm' => "您确定要为此用户开通实单吗？"
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('删除', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-xs red ajax-post',
                        'hide-data' => 'true',
                        'data-confirm' => "您确定要删除此用户吗？"
                    ]);
                },
            ],
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