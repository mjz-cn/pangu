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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script language="JavaScript">
        var BaseUrl = '<?=Yii::getAlias('@web')?>';
        var nav_url = undefined;
    </script>
</head>
<body class="page-container-bg-solid page-md" style=" background:#eff3f8;">
<?php $this->beginBody() ?>


<?= $this->renderFile('@app/views/layouts/menu.php') ?>

<div class="page-container">
    <div class="page-content-wrapper">
        <div class="page-head">
        </div>
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="container">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php \frontend\assets\LayoutAsset::register($this); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
