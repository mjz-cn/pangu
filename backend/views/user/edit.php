<?php

use yii\helpers\Html;
use common\core\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\User */
/* @var $form ActiveForm */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '添加用户';
$this->params['title_sub'] = '添加前台用户';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

$modelName = (new ReflectionClass($model))->getShortName();
?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 内容信息</span>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->

        <?php $form = ActiveForm::begin([
            'options'=>[
                'class'=>"form-aaa "
            ]
        ]); ?>

        <?= $form->field($model, 'username')->iconTextInput([
            'class'=>'form-control c-md-2',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => 'username'
        ])->label('用户名') ?>

        <div class="form-group">
            <label>密码</label>
            <div class="">
                <div class="input-icon left">
                    <i class="icon-lock"></i>
                    <input type="password" class="form-control c-md-2"
                           name=<?= '"'.$modelName.'[password]"'  ?> placeholder="密码不变请留空" />
                </div>
            </div>
        </div>

        <?= $form->field($model, 'email')->iconTextInput([
            'class'=>'form-control c-md-2',
            'iconPos' => 'left',
            'iconClass' => 'icon-envelope',
            'placeholder' => 'Email Address'
        ])->label('邮箱') ?>

        <?= $form->field($model, 'phone')->iconTextInput([
            'class'=>'form-control c-md-2',
            'iconPos' => 'left',
            'iconClass' => 'fa fa-phone',
            'placeholder' => 'Phone'
        ])->label('电话') ?>
        
        <?= $form->field($model, 'status')->radioList(['1'=>'正常','0'=>'隐藏'])->label('用户状态') ?>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 确定', ['class' => 'btn blue ajax-post','target-form'=>'form-aaa']) ?>
            <?= Html::button('取消', ['class' => 'btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <!-- END FORM-->
    </div>
</div>

<!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>
jQuery(document).ready(function() {
    highlight_subnav(window.location.pathname); //子导航高亮
});
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
