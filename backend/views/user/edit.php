<?php

use common\helpers\ArrayHelper;
use common\models\records\Region;
use common\models\records\User;
use kartik\helpers\Html;
use kartik\widgets\Select2;
use common\core\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\records\User */
/* @var $userInfoModel common\models\records\NormalUserInfo */
/* @var $form ActiveForm */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '添加用户';
$this->params['title_sub'] = '添加前台用户';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">

    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <i class="icon-settings font-red-sunglo"></i>
                <span class="caption-subject bold uppercase"> 内容信息</span>
            </div>
        </div>
        <hr>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => "form-aaa"
            ]
        ]); ?>

        <div class="row">
            <div class="col-md-6">

                <?= $form->field($model, 'email')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-envelope',
                    'placeholder' => '邮箱'
                ])->label('邮箱') ?>

                <?= $form->field($model, 'phone')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'fa fa-phone',
                    'placeholder' => '电话'
                ])->label('电话') ?>

                <?= $form->field($model, 'wechat')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'fa fa-phone',
                    'placeholder' => '微信账号'
                ])->label('微信账号') ?>

                <?= $form->field($model, 'qq')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'fa fa-phone',
                    'placeholder' => 'QQ'
                ])->label('QQ') ?>
                <div class="form-group">
                    <div>
                        <label>省 市 区</label>
                        <span class="help-inline"></span>
                    </div>
                    <div class="col-md-3" style="padding-left:0px;">
                        <?=\kartik\widgets\Select2::widget([
                            'model' => $model,
                            'attribute' => 'province',
                            'data' => ArrayHelper::map(Region::find()->where(['parent_code'=>0])->asArray()->all(), 'code', 'fullname')
                        ]);?>
                    </div>
                    <div class="col-md-3">
                        <?=\kartik\widgets\DepDrop::widget([
                            'model' => $model,
                            'attribute' => 'city',
                            'options' => ['placeholder' => '选择'],
                            'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions'=>[
                                //'initialize' => true,
                                //'initDepends'=>['order-province'],
                                'depends'=>[Html::getInputId($model, 'province')],
                                'url' => Url::to(['/public/region','sid'=>$model['city']]),
                                'loadingText' => '加载中',
                            ]
                        ]);?>
                    </div>
                    <div class="col-md-3">
                        <?=\kartik\widgets\DepDrop::widget([
                            'model' => $model,
                            'attribute' => 'area',
                            'options' => ['placeholder' => '选择'],
                            'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions'=>[
                                'initialize' => true,
                                'initDepends'=>[Html::getInputId($model, 'province')],
                                'depends'=>[Html::getInputId($model, 'city')],
                                'url' => Url::to(['/public/region','sid'=>$model['area']]),
                                'loadingText' => '加载中',
                            ]
                        ]);?>
                    </div>
                    <div style="clear:both;"></div>
                </div>

                <?= $form->field($model, 'real_name')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '真实姓名'
                ]) ?>
            </div>

            <div class="col-md-6">

                <?= $form->field($model, 'gender')->radioList(['0' => '女', '1' => '男']) ?>

                <?= $form->field($model, 'card_id')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'type' => 'number',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '身份证号'
                ]) ?>

                <?= $form->field($model, 'bank_account')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'type' => 'number',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '银行账户'
                ]) ?>

                <?= $form->field($model, 'bank_name')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '开户行名称'
                ]) ?>

                <?= $form->field($model, 'bank_username')->iconTextInput([
                    'class' => 'form-control c-md-10',
                    'iconPos' => 'left',
                    'iconClass' => 'icon-user',
                    'placeholder' => '银行帐户姓名'
                ]) ?>

            </div>

        </div>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 确定', ['class' => 'btn blue ajax-post', 'target-form' => 'form-aaa']) ?>
            <?= Html::resetButton('取消', ['class' => 'btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <!-- END FORM-->
    </div>


</div>

<!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>
jQuery(document).ready(function() {
highlight_subnav('user/index'); //子导航高亮
});
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
