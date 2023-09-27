<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Quotation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quotations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="quotation-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'id',
            'quotation_id',
            'doc_no',
            'rev_no',
            'doc_name',
            'doc_title',
            'project_name',
            'total_price',
            'total_price_after_disc',
            'max_total_price_after_disc',
            'total_discount',
            'total_discount2',
            'total_discount_value',
            'max_total_discount_value',
            'accumulate_discount_rate',
            'max_accumulate_discount_rate',
            'client',
            'status',
            'status_by',
            'status_at',
            'master',
            'slave',
            'reason:ntext',
            'company',
            'code',
            'address:ntext',
            'person',
            'email:email',
            'telephone',
            'mobile',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
