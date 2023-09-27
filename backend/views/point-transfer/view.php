<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PointLedger */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Point Ledgers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="point-ledger-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'user.username',
            'userIdFrom.username',
            'userIdTo.username',
            'debit',
            'credit',
            'balance',
            'accumulate_point',
            'remark',
            'createdBy.username',
            'created_at:datetime',
            'updatedBy.username',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
