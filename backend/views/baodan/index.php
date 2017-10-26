<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:01
 */

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
        'value' => function($model) {return "";}
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
        'class' => 'yii\grid\ActionColumn',
        'header' => '操作',
        'template' => '{sure} {delete}',
        //'options' => ['width' => '200px;'],
        'buttons' => [
            'sure' => function ($url, $model, $key) {
                return Html::a('确定', ['check','id'=>$model->id], [
                    'title' => Yii::t('app', '确定'),
                    'class' => 'btn btn-xs',
                    'data-method' => "post",
                    'data-confirm' => "您确定通过吗？"
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('删除', ['delete','id'=>$model->id], [
                    'title' => Yii::t('app', '删除'),
                    'class' => 'btn btn-xs',
                    'data-method' => "post",
                    'data-confirm' => "您确定要删除此项吗？"
                ]);
            },
        ],
    ],
];

if ($searchModel->status == \backend\models\search\BaodanSearch::STATUS_CHECKED) {
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
            'label' => '查看报单',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a("查看报单会员", ["members", "id" => $model->id], [
                    'class' => 'btn btn-xs',
                ]);
            }
        ],
        [
            'label' => '申请日期',
            'attribute' => 'create_time',
            'format' => ['date', 'php:Y-m-d H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{sure} {delete}',
            //'options' => ['width' => '200px;'],
            'buttons' => [
                'sure' => function ($url, $model, $key) {
                    return Html::a('确定', ['check','id'=>$model->id], [
                        'title' => Yii::t('app', '确定'),
                        'class' => 'btn btn-xs',
                        'data-method' => "post",
                        'data-confirm' => "您确定通过吗？"
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('删除', ['delete','id'=>$model->id], [
                        'title' => Yii::t('app', '删除'),
                        'class' => 'btn btn-xs',
                        'data-method' => "post",
                        'data-confirm' => "您确定要删除此项吗？"
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
        </div>
        <div class="portlet-body">
            <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
            <div>
                <?php echo $this->render('_check_hb_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
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