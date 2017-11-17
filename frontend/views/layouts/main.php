<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\assets\IeAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
IeAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?>  | 佰草兰德 </title>
    <?php $this->head() ?>
    <script language="JavaScript">
        var BaseUrl = '<?=Yii::getAlias('@web')?>';
        var nav_url = undefined;
    </script>
</head>
<body class="page-container-bg-solid page-md" style="background:#fff;">
<?php $this->beginBody() ?>


<?= $this->renderFile('@app/views/layouts/menu.php') ?>

<div class="page-container">
    <div class="page-content-wrapper">
        <div class="page-head">
        </div>
        <div class="page-content-wrapper">
            <div class="page-content" style="background:#fff;">
                <div class="container">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fixed {
        position: fixed !important;
    }

    .alert {
        color: #c09853;
        font-weight: bold;
        border: 1px solid #fbeed5;
        background-color: #fcf8e3;
    }

    #top-alert {
        display: block;
        top: 40px;
        right: 20px;
        z-index: 10052;
        margin-top: 20px;
        padding-top: 12px;
        padding-bottom: 12px;
        overflow: hidden;
        font-size: 16px;
    }

    .alert-error {
        color: white;
        border-color: #eed3d7;
        background-color: #FF6666;
    }

    .alert-success {
        color: #468847;
        background-color: #CCFF99;
        border-color: #eed3d7;
    }

    @media (max-width: 768px) {
        .alert_left {
            left: 20px;
        }
    }

    @media (min-width: 768px) {
        .alert_left {
            left: 245px;
        }
    }
</style>
<div id="top-alert" class="fixed alert alert-error alert_left" style="display: none;">
    <button class="close" style="margin-top:6px;">&times;</button>
    <div class="alert-content">这是Ajax弹出内容</div>
</div>
<?php \frontend\assets\LayoutAsset::register($this); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
