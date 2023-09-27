<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PointDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Point Collection';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-doc-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <!--<div class="card-tools">
            <?//= Html::a('Create Point Doc', ['create'], ['class' => 'btn btn-success']) ?>
        </div>-->
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <?php
            $addon = <<< HTML
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-calendar-alt"></i>
                </span>
            </div>
            HTML;
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions'=>['class'=>'table table-striped table-bordered table-responsive'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'doc_no',
                    [
                        'label'=>'User',
                        'attribute'=>'userIdTo.username'
                    ],
                    //'id',
                    //'quotation_id',
                    //'user_id_from',
                    //'user_id_to',
                    //'doc_no',
                    //'doc_type',
                    //'ref_no',
                    //'remark',
                    'sales_point_rate',
                    [
                        'attribute'=>'total_sales_point',
                        'format'=>['decimal', 2]
                    ],
                    [
                        'attribute'=>'total_payment_received',
                        'format'=>['decimal', 2]
                    ],
                    [
                        'label'=>'Total price',
                        'attribute'=>'quotation.total_price_after_disc',
                        'format'=>['decimal', 2]
                    ],
                    [
                        'attribute'=>'total_debit_sales_point',
                        'format'=>['decimal', 2]
                    ],
                    //'bf',
                    //'total_point',
                    //'status',
                    [
                        'attribute'=>'quotation.total_discount',
                        'format'=>['decimal', 2]
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\PointDoc::getStatusList(), [
                            'class' => 'form-control',
                            'prompt' => 'All'
                        ]),
                        'format' => 'orderStatus'
                    ],
                    [
                        'attribute'=>'created_at',
                        'label'=>'Created At',
                        'format'=>'text',
                        'filter'=> '<div class="input-group drp-container">'.
                            DateRangePicker::widget([
                            'name'=>'KV[created_at]',
                            'value'=>'',
                            'useWithAddon'=>true,
                            'pluginOptions'=>[
                                'singleDatePicker'=>true,
                                'showDropdowns'=>true
                            ]
                        ]). $addon. "</div>",
                       'content'=>function($data){
                            return Yii::$app->formatter->asDatetime($data['created_at'], "php:d/m/Y");
                        }
                    ],
                    //'status_by',
                    //'status_at',
                    //'created_by',
                    //'created_at',
                    //'updated_by',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete} {done} {cancel}',
                        'buttons' => [
                            'update' => function($url, $model) {
                                return !in_array($model->status, [$model::STATUS_DONE,$model::STATUS_CANCEL]) ? Html::a('<span class="fas fa-pencil-alt"></span>', ['update', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'UPDATE'),
                                ]) : ""; 
                            },
                            'delete' => function($url, $model) {
                                return !in_array($model->status, [$model::STATUS_DONE,$model::STATUS_CANCEL]) && Yii::$app->user->can('approve-point-collection') ? Html::a('<span class="fas fa-trash-alt"></span>', ['delete', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => 'Are you sure you want to delete this document?'
                                ]) : ""; 
                            },
                            'cancel' => function($url,$model) {
                                return !in_array($model->status, [$model::STATUS_DONE,$model::STATUS_CANCEL]) && Yii::$app->user->can('approve-point-collection') ? Html::a('<span class="fas fa-times-circle text-danger"></span>', ['update-cancel', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Cancel'),
                                    'data-confirm' => 'Are you sure you want to cancel this document?'
                                ]) : ""; 
                            },
                            'done' => function($url,$model) {
                                return !in_array($model->status, [$model::STATUS_DONE,$model::STATUS_CANCEL]) && Yii::$app->user->can('approve-point-collection') ? Html::a('<span class="fas fa-check-circle text-success"></span>', ['update-done', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Done'),
                                    'data-confirm' => 'Are you sure you want to approve this document?'
                                ]) : ""; 
                            }
                        ]
                    ],
                ],
            ]); ?>

        </div>
        <!-- /.card-body -->
    </div>


</div>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php 
    Modal::begin([
        'title' => 'Quotation',
        'id' => 'modal',
        'size' => 'modal-xl',
    ]);

    echo "<div id='modalContent'><div class='spinner-border' role='status'> <span class='sr-only'>Loading...</span> </div></div>";

    Modal::end();

?>