<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RelationGraphForm */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['user-tree'],
    'method' => 'post',
    'options'=> [
        'class'=>"form-user-tree-search",
        'data-pjax' => false, //开启pjax搜索
    ]]);

    $level_arr = [];
    for ($i = 0; $i < 11; $i ++) {
        $level_arr[$i] = $i;
    }
?>
<div class="row">
    <div class="col-md-1">
    <?= $form->field($model, 'user_id')->textInput()->label("用户ID") ?>
    </div>
    <div class="col-md-1">
    <?= $form->field($model, 'up_level')->dropDownList(
        $level_arr,
        ['class'=>'form-control'])->label('上层');
    ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'down_level')->dropDownList(
            $level_arr,
            ['class'=>'form-control'])->label('下层');
        ?>
    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
        <?= Html::submitButton('确定', ['class' => 'btn btn-primary ajax-get-relation',
            'target-form'=>'form-user-tree-search']) ?>
        <?= Html::a('重置', [Yii::$app->request->pathInfo],['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
