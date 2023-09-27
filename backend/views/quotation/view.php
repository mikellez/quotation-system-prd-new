<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Quotation */

$this->title = $model->doc_no;
$this->params['breadcrumbs'][] = ['label' => 'Quotations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="quotation-view">

    <!--<h1><?//= Html::encode($this->title) ?></h1>-->

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

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Quotation: <?= $model->doc_no?></h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'created_at:datetime',
                            'updated_at:datetime',
                            'doc_no',
                            'doc_name',
                            [
                                'label'=>'Company',
                                'format'=>['raw'],
                                'value' =>    function() use ($model) {
                                    return Html::a($model->company, ['client/view', 'id'=>$model->client]);
                                },
                            ],
                            'person',
                            'address:ntext',
                            'email:email',
                            'telephone',
                            'mobile',
                            [
                                'attribute'=>'payment_tnc',
                                'format'=>'html'
                            ],
                            'total_price:decimal',
                            'status:orderStatus',
                            [
                                'label'=>'Status By',
                                'attribute'=>'statusBy.username',
                                'visible'=> Yii::$app->user->can('update-quotation-status'),
                                'format'=>['raw'],
                                'value' =>    function() use ($model) {
                                    return Html::a($model->statusBy->username, ['user/view', 'id'=>$model->created_by]);
                                },
                            ],
                            [
                                'label'=>'Status At',
                                'attribute'=>'status_at:datetime',
                                'visible'=> Yii::$app->user->can('update-quotation-status'),
                            ],
                            //'statusBy.username',
                            //'status_at:datetime',
                            [
                                'attribute'=>'reason',
                                'format'=>['html']
                            ],
                            [
                                'label'=>'Created By',
                                'attribute'=>'createdBy.username',
                                'format'=>['raw'],
                                'value' =>    function() use ($model) {
                                    return Html::a($model->createdBy->username, ['user/view', 'id'=>$model->created_by]);
                                },
                            ],
                            [
                                'label'=>'Updated By',
                                'attribute'=>'updatedBy.username',
                                'format'=>['raw'],
                                'visible'=> Yii::$app->user->can('update-quotation-status'),
                                'value' =>    function() use ($model) {
                                    return Html::a($model->updatedBy->username, ['user/view', 'id'=>$model->updated_by]);
                                },
                            ],
                            //'createdBy.username',
                            //'updatedBy.username',
                        ],
                    ]) ?>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
