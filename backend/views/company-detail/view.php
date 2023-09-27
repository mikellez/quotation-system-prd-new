<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CompanyDetail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['/company/update?id='.$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Company Details'];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-detail-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'company0.company',
            'payment_tnc:ntext',
            'createdBy.username',
            'created_at:datetime',
            'updatedBy.username',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
