<?php

use common\core\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收货地址';
//$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'header' => '账号',
        'attribute' => 'user.username',
    ],
    [
        'header' => '详细地址',
        'value' => 'detailAddress',
    ],
    [
        'header' => '收货人姓名',
        'value' => 'name'
    ],
    [
        'header' => '电话',
        'value' => 'phone'
    ],
    [
        'header' => '邮编',
        'value' => 'postcode'
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'header' => '操作',
        'template' => '{update} {delete}',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                return Html::a('编辑', ['update', 'id' => $key], [
                    'title' => Yii::t('app', '更新'),
                    'class' => 'btn btn-xs btn-default'
                ]);
            }
        ],
    ],
];
?>

<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">管理收货地址 (最多有三个收货地址)</span>
        </div>
        <div class="actions">
            <div class="btn-group btn-group-devided">
                <?php if (!empty($showCreateBtn)) echo Html::a('添加', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php if (!empty($showSearchModel)) echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                       id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>