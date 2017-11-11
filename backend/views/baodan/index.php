<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:01
 */

use common\models\records\Baodan;
use kartik\helpers\Html;
use common\core\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\BaodanSearch */
/* @var $model backend\models\MangageHuobiForm */


$this->title = '已审核';
$this->params['title_sub'] = '管理用户信息';

$columns = [
    [
        'label' => '报单中心编号',
        'value' => 'name'
    ],
    [
        'label' => '用户账号',
        'value' => 'user.username'
    ],
    [
        'label' => '会员姓名',
        'value' => 'user.real_name'
    ],
    [
        'label' => '会员级别',
        'value' => 'user.levelText'
    ],
    [
        'label' => '代理级别',
        'value' => function ($model) {
            return "";
        }
    ],
    [
        'label' => '购买报单币',
        'value' => 'baodanbi'
    ],
    [
        'label' => '申请日期',
        'attribute' => 'create_time',
        'format' => ['date', 'php:Y-m-d H:i']
    ],
    [
        'header' => '状态',
        'value' => 'statusText'
    ],
    [
        'header' => '操作',
        'format' => 'raw',
        'value' => function ($model) {
            $btnArr = [];
            if ($model->status == Baodan::STATUS_CHECKING) {
                $btnArr[] = Html::a('通过', ['index', 'baodan_id' => $model->id, 'baodan_status' => Baodan::STATUS_APPROVE], [
                    'title' => Yii::t('app', '确定'),
                    'class' => 'btn btn-xs btn-default',
                    'data-method' => "post",
                    'data-confirm' => "您确定通过吗？"
                ]);
                $btnArr[] = Html::a('拒绝', ['index', 'baodan_id' => $model->id, 'baodan_status' => Baodan::STATUS_REJECT], [
                    'title' => Yii::t('app', '确定'),
                    'class' => 'btn btn-xs btn-primary',
                    'data-method' => "post",
                    'data-confirm' => "您确定拒绝吗？"
                ]);
                $btnArr[] = Html::a('删除', ['delete', 'id' => $model->id], [
                    'title' => Yii::t('app', '删除'),
                    'class' => 'btn btn-xs btn-danger',
                    'data-method' => "post",
                    'data-confirm' => "您确定要删除此项吗？"
                ]);
            } elseif ($model->status == Baodan::STATUS_APPROVE) {
                $btnArr[] = Html::a('冻结', ['index', 'baodan_id' => $model->id, 'baodan_status' => Baodan::STATUS_BAN], [
                    'title' => Yii::t('app', '冻结'),
                    'class' => 'btn btn-xs',
                    'data-method' => "post",
                    'data-confirm' => "您确定要冻结此报单中心吗？"
                ]);
                $btnArr[] = Html::a("查看报单会员", ["members", "id" => $model->id], [
                    'class' => 'btn btn-xs',
                ]);
            }
            return implode('', $btnArr);
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
            <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
            <div>
                <?php echo $this->render('_index_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
            </div>
            <div class="table-container">
                <form class="ids">
                    <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                           id="<?= Yii::$app->request->csrfParam ?>"
                           value="<?= Yii::$app->request->csrfToken ?>">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider, // 列表数据
                        /* 定义列表格式 */
                        'columns' => $columns,
                    ]); ?>
                </form>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
    <!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>
    jQuery(document).ready(function() {
    var nav_url = '/baodan/index?status=' + urlParam('status');
    highlight_subnav(nav_url); //子导航高亮
    });
<?php $this->endBlock() ?>
    <!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>