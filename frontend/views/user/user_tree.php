<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\NormalUser */
/* @var $searchModel backend\models\RelationGraphForm */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '用户系谱图';
$this->params['title_sub'] = '管理用户信息';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

/* 加载页面级别资源 */
\common\assets\UserTreeAsset::register($this);

// 展示树状图
?>
<div class="panel">
    <div class="panel-heading">
        <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">用户系谱图</span>
        </div>
    </div>
    <div class="panel-body">
        <div>
            <?php echo $this->render('_user_tree_search', ['searchModel' => $searchModel]); ?>
        </div>
        <hr>
        <!--   -->
        <div id="user-tree-container">没有内容</div>
    </div>
</div>