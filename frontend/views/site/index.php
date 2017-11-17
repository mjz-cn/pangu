<?php

/* @var $this yii\web\View */
/* @var $wallet \common\models\records\Wallet */

$this->title = '商务会员管理系统';

$broker = Yii::$app->user->identity->broker;

?>
<div class="site-index row">

    <div class="col-md-3" style="margin-top: 50px">
        <ul class="list-group">
            <li class="list-group-item">用户: <?= Yii::$app->user->identity->username ?></li>
            <li class="list-group-item ">奖金币: <?= number_format($wallet->jiangjin) ?></li>
            <li class="list-group-item">电子币: <?= number_format($wallet->dianzi) ?></li>
            <li class="list-group-item">累计收入: <?= number_format($wallet->total_jiangjin) ?></li>
            <li class="list-group-item">领路老师账号: <?= $broker === null ? '-' : $broker->username; ?></li>
            <li class="list-group-item">领路老师姓名: <?= $broker === null ? '-' : $broker->real_name ?></li>
        </ul>
    </div>

    <div class="col-md-9">
        <div class="jumbotron">
            <h1>欢迎!</h1>
        </div>

        <div class="body-content">

            <div class="row">

            </div>

        </div>
    </div>
</div>
