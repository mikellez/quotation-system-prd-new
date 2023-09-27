<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PointDoc */

$this->title = $model->doc_no;
$this->params['breadcrumbs'][] = ['label' => 'Point Collection', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="point-doc-view">

    <h4>Point Collection</h4>

    <p>
        <?php if($model->status != $model::STATUS_DONE):?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif;?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'quotation_id',
            //'user_id_from',
            //'user_id_to',
            'doc_no',
            'userIdTo.username',
            'doc_type',
            //'ref_no',
            //'remark',
            [ 
                'attribute'=>'sales_point_rate',
                'format'=>['decimal', 2]
            ],
            [
                'attribute'=>'total_sales_point',
                'format'=>['decimal', 2]
            ],
            [

                'attribute'=>'total_payment_received',
                'format'=>['decimal', 2]
            ],
            [
                'attribute'=>'total_debit_sales_point',
                'format'=>['decimal', 2]
            ],
            [
                'attribute'=>'bf',
                'format'=>['decimal', 2]
            ],
            [
                'attribute'=>'total_point',
                'format'=>['decimal', 2]
            ],
            [
                'attribute'=>'quotation.total_discount',
                'format'=>['decimal', 2]
            ],
            //'status',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($model, 'status', \common\models\PointDoc::getStatusList(), [
                    'class' => 'form-control',
                    'prompt' => 'All'
                ]),
                'format' => 'orderStatus'
            ],
            //'status_by',
            //'status_at',
            'createdBy.username',
            'created_at:datetime',
            //'updated_by',
            //'updated_at',
        ],
    ]) ?>

</div>
