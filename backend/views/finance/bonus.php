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
        'value' => 'reg_money',
    ],
    [
        'label' => '福利级别',
        'defaultValue' => '暂无'
    ],
    [
        'label' => '联系电话',
        'value' => 'phone',
    ],
    [
        'label' => '是否实单',
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
        'label' => '奖金余额',
        'value' => 'wallet.jiangjin',
    ],
    [
        'label' => '电子币余额',
        'value' => 'wallet.dianzi',
    ],
    [
        'label' => '消费币余额',
        'value' => 'wallet.chongxiao',
    ],
    [
        'label' => '谁开通',
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
