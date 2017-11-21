<?php

/* @var $this yii\web\View */
/* @var $wallet \common\models\records\Wallet */

$this->title = '商务会员管理系统';

$broker = Yii::$app->user->identity->broker;

?>
<div class="site-index row" style="margin-top: 50px;min-width: 500px">

    <div class="col-md-3 col-sm-4 col-xs-6" >
        <ul class="list-group">
            <li class="list-group-item site-li">用户: <?= Yii::$app->user->identity->username ?></li>
            <li class="list-group-item site-li">奖金币: <?= number_format($wallet->jiangjin) ?></li>
            <li class="list-group-item site-li">累计奖金币收入: <?= number_format($wallet->total_jiangjin) ?></li>
            <li class="list-group-item site-li">电子币: <?= number_format($wallet->dianzi) ?></li>
            <li class="list-group-item site-li">累计电子币: <?= number_format($wallet->total_dianzi) ?></li>
            <li class="list-group-item site-li">领路老师账号: <?= $broker === null ? '-' : $broker->username; ?></li>
            <li class="list-group-item site-li">领路老师姓名: <?= $broker === null ? '-' : $broker->real_name ?></li>
        </ul>
    </div>

    <div class="col-md-9 col-sm-8 col-xs-6">
        <div class="">
            <img src="<?= Yii::getAlias('@web/images/1.gif') ?>">
        </div>

        <div class="body-content">

            <div class="row">

            </div>

        </div>
    </div>
</div>
<style>
    .site-li {
        border: none;
        padding: 12px 9px 14px 46px;
        background-repeat: no-repeat;
        background-color: transparent;
        background-image: url(<?=Yii::getAlias('@web/images/red.gif')?>);
    }
</style>
