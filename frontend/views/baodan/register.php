<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/3
 * Time: 上午9:20
 */

/* @var $model common\models\records\Baodan */

use common\core\ActiveForm;
use common\helpers\Html;


?>

<div class="panel panel-default">
    <div class="panel-heading">
        申请报单中心, 申请人：<span class="label label-success"><?= Yii::$app->user->identity->username ?></span>
    </div>

    <div class="panel-body">

        <div>
            <h3 class="text-danger">报单中心说明：</h3>

            <p>1、报单中心可以开通属于自己报单中心的会员；<br>
                2、报单中心应有舒适的办公室,才能得相应的报单费；</p>

        </div>

        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => "form-aaa "
            ]
        ]); ?>

        <?= $form->field($model, 'baodanbi')->textInput(['disabled' => 'true']) ?>
        <?= $form->field($model, 'name')->textInput(['disabled' => 'true']) ?>

        <?php if ($model->isNewRecord): ?>
            <div class="form-actions">
                <?= Html::submitButton('<i class="icon-ok"></i> 确定', ['class' => 'btn blue']) ?>
            </div>
        <?php elseif ($model->status == \common\models\records\Baodan::STATUS_CHECKING): ?>
            <span class="text-danger">申请正在审核中</span>
        <?php elseif ($model->status == \common\models\records\Baodan::STATUS_REJECT): ?>
            <span class="text-danger">申请被拒绝</span>
        <?php else: ?>
            <span class="text-danger">您已经是报单中心了</span>
        <?php endif ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>