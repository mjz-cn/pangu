<?php

/* @var $this yii\web\View */

$this->title = '商务会员管理系统';
?>
<div class="site-index row">

    <div class="col-md-3" style="margin-top: 50px">
        <ul class="list-group">
            <li class="list-group-item"><i class="icon-home icons"></i>
                用户: <?= Yii::$app->user->identity->username ?></li>
            <li class="list-group-item">推荐人数: </li>
            <li class="list-group-item ">奖金币: </li>
            <li class="list-group-item">电子币: </li>
            <li class="list-group-item">累计收入: </li>
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
