<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\Baodan */

$this->title = 'Create Baodan';
$this->params['breadcrumbs'][] = ['label' => 'Baodans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="baodan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
