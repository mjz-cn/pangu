<?php

use common\core\ActiveForm;
use common\models\search\UserTreeSearch;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $form common\core\ActiveForm */
/* @var $searchModel \common\models\search\UserTreeSearch */
?>

<?php $form = ActiveForm::begin([
    'action' => [Yii::$app->request->pathInfo],
    'method' => 'post',
    'options' => [
        'id' => 'user-tree-search-form'
        //'class'=>"form-inline",
    ]
]); ?>
<div class="row">

    <div class="col-md-2">
        <?= $form->field($searchModel, "user_id")->widget(Select2::classname(), [
            'data' => [],
            'options' => ['placeholder' => '选择用户'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'dataType' => 'json',
                'ajax' => [
                    'url' => \yii\helpers\Url::toRoute('/user/search'),
                    'delay' => 250,
                    'data' => new JsExpression('function(params) { return {user_name:params.term}; }'),
                    'processResults' => new JsExpression('function(data, params) {return {results: data};}'),
                ],
                'templateResult' => new JsExpression('function(user) { return user.username; }'),
                'templateSelection' => new JsExpression('function (user) { return user.username; }'),
            ],
        ], ['class' => 'c-md-12'])->label('选择用户'); ?>
    </div>

    <div class="col-md-1">
        <?= $form->field($searchModel, 'orientation', ['inputOptions' => ['class' => 'form-control']])->selectList([
            UserTreeSearch::ORIENTATION_DOWN => '向下',
            UserTreeSearch::ORIENTATION_UP => '向上',
        ]) ?>
    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn blue']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
