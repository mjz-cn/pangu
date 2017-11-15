<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:01
 */

use common\core\ActiveForm;
use common\helpers\TransactionHelper;
use common\models\records\ActiveUserRatioLog;
use common\models\records\ExchangeLog;
use common\models\records\RechargeLog;
use kartik\helpers\Html;
use common\core\GridView;

/* @var $this yii\web\View */
/* @var $model ExchangeLog */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\ExchangeSearch */

$this->title = '会员报单审核';
$this->params['title_sub'] = '管理用户信息';

$columns = [
    [
        'header' => '被激活用户账号',
        'value' => 'fromUser.username'
    ],
    [
        'header' => '被激活用户姓名',
        'value' => 'fromUser.real_name'
    ],
    [
        'header' => '注册资金',
        'value' => 'fromUser.reg_money'
    ],
    [
        'header' => '用户账号',
        'value' => 'user.username'
    ],
    [
        'header' => '用户账号姓名',
        'value' => 'user.username'
    ],
    [
        'header' => '奖金',
        'value' => 'jiangjin'
    ],
    [
        'header' => '描述',
        'value' => 'desc'
    ],
    [
        'header' => '激活日期',
        'value' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
    ],
    [
        'header' => '审核状态',
        'format' => 'raw',
        'value' => function ($model) {
            if ($model->status != ActiveUserRatioLog::STATUS_CHECKING) {
                return $model->statusText;
            }
            $btn1 = Html::a('通过', ['actived-user-check', 'a_id' => $model->id, 'a_status' => ActiveUserRatioLog::STATUS_APPROVE], [
                'title' => Yii::t('app', '通过申请'),
                'class' => 'btn btn-xs btn-danger',
                'data-method' => 'post',
                'data-confirm' => '确定通过吗?'
            ]);
            $btn2 = Html::a('拒绝', ['actived-user-check', 'a_id' => $model->id, 'a_status' => ActiveUserRatioLog::STATUS_REJECT], [
                'title' => Yii::t('app', '拒绝申请'),
                'class' => 'btn btn-xs btn-info',
                'data-method' => 'post',
                'data-confirm' => '确定拒绝吗?'

            ]);
            return $btn1 . $btn2;
        }
    ]
];

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="icon-settings font-dark"></i> 激活用户产生奖金审核
    </div>
    <div class="panel-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <div>
            <?php echo $this->render('_actived_user_check_search', ['model' => $searchModel]); ?>
        </div>
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                       id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider, // 列表数据
                    'columns' => $columns
                ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
