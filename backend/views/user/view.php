<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['edit', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'password',
            'salt',
            'email:email',
            'phone',
            'role',
            'reg_ip',
            'last_login_time:datetime',
            'last_login_ip',
            'image',
            'broker_id',
            'broker_path',
            'referrer_id',
            'real_name',
            'gender',
            'card_id',
            'bank_account',
            'bank_name',
            'status',
            'update_time:datetime',
            'create_time:datetime',
        ],
    ]) ?>

</div>
