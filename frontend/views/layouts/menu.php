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
                    $baodanModel = Yii::$app->user->identity->getBaoDan();

                    $menuItems = [
                        ['label' => '首页', 'options' => ['class' => 'menu-dropdown'], 'url' => ['/site/index']],
                        ['label' => '会员资料', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                            ['label' => '资料编辑', 'url' => '/user/edit'],
                            ['label' => '修改密码', 'url' => '/user/reset-password'],
                            ['label' => '收获地址', 'url' => '/user/address'],
                        ]],
                        ['label' => '部门情况', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                            ['label' => '推荐会员', 'url' => '/user/index?status=1'],
                            ['label' => '会员网络', 'url' => '/user/user-tree'],
                        ]]
                    ];
                    if (!empty($baodanModel)) {
                        $menuItems[] = ['label' => '代理中心', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                            ['label' => '注册会员', 'url' => '/user/add'],
                            ['label' => '激活会员', 'url' => '/user/index?status=2'],
                            ['label' => '已激活会员', 'url' => '/user/index?status=3'],
                            ['label' => '审核报单', 'url' => '/baodan/check'],
                            ['label' => '申请中心', 'url' => '/baodan/register'],
                        ]];
                    } else {
                        $menuItems[] = ['label' => '代理中心', 'options' => ['class' => 'menu-dropdown'], 'items' => [
                            ['label' => '申请中心', 'url' => '/baodan/register'],
                        ]];
                    }

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
                    $menuItems = [['label' => Yii::$app->user->identity->username. '(退出)', 'options' => ['style' => 'color: #fff'], 'url' => ['/site/logout']]];
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


