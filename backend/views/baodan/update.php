<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\Baodan */

$this->title = 'Update Baodan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Baodans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="baodan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
