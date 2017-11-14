<?php

use common\core\ActiveForm;
use common\models\search\UserTreeSearch;
use yii\helpers\Html;

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
        <?= $form->field($searchModel, 'username', ['inputOptions' => ['class' => 'form-control']])
            ->textInput()->label('选择用户：') ?>
    </div>


    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn blue']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
