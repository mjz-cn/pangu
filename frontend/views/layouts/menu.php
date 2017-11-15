<?php

use common\helpers\Html;
use common\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

?>

<div class="page-header">
    <div class="page-header-menu">
        <div class="container">
            <div class="hor-menu">
                <?php
                if (!Yii::$app->user->isGuest) {
                    $menuItems = [
                        ['label' => '首页', 'options' => ['class' => 'menu-dropdown'], 'url' => ['/site/index']],
                        ['label' => '会员资料', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                            ['label' => '资料收集', 'url' => '/user/edit'],
                            ['label' => '修改密码', 'url' => '/user/reset-password'],
                            ['label' => '收货地址', 'url' => '/address/index'],
                        ]],
                        ['label' => '部门情况', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                            ['label' => '会员网络', 'url' => '/user/user-tree'],
                            ['label' => '审核报单', 'url' => '/baodan/check'],
                            ['label' => '注册会员', 'url' => '/user/add'],
                            ['label' => '部门会员列表', 'url' => '/user/index'],
                        ]]
                    ];

                    $menuItems[] = ['label' => '财务管理', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                        ['label' => '奖金明细', 'url' => '/finance/index'],
                        ['label' => '帐户提现', 'url' => '/finance/exchange'],
                        ['label' => '帐户转账', 'url' => '/finance/transfer'],
                        ['label' => '奖金币转换', 'url' => '/finance/bonus-to-dianzibi'],
                        ['label' => '重复报单', 'url' => '/finance/recharge']
                    ]];

                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav'],
                        'items' => $menuItems,
                    ]);
                }
                ?>
            </div>
            <div class="hor-menu" style="float: right">
                <?php
                if (Yii::$app->user->isGuest) {
                    $menuItems = [['label' => '登录', 'options' => ['style' => 'color: #fff'], 'url' => ['/site/login']]];
                } else {
                    $menuItems = [['label' => Yii::$app->user->identity->username . '(退出)', 'options' => ['style' => 'color: #fff'], 'url' => ['/site/logout']]];
                }
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


