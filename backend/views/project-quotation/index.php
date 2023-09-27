<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use kartik\daterange\DateRangePicker;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project Quotations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <?= Html::a('Create Project Quotation', ['create'], ['class' => 'btn btn-success btn-sm']) ?>            
        </div>
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

            <br/><br/>

            <?php 

            function wrapPjax($grid) {
                ob_start();

                Pjax::begin(['timeout' => 10000]);
                echo $grid;
                Pjax::end();
                
                return ob_get_clean();
            }

            $columns = [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                'doc_no',
                [
                    'attribute' => 'status',
                    'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\Quotation::getStatusList(), [
                        'class' => 'form-control',
                        'prompt' => 'All'
                    ]),
                    'format' => 'orderStatus',
                    'headerOptions'=>[
                        'class'=>'text-center'
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center'
                    ]
                ],
                //'status:orderStatus',
                //'statusBy.username',
                //'status_at:datetime',
                //'reason:ntext',
                //'address:ntext',
                'person',
                [
                    'attribute'=> 'createdBy.username',
                    'header'=> 'Sales Person',
                    'filter'=>true
                ],
                'email:email',
                [
                    'attribute'=>'telephone',
                    'content'=>function($model) {
                        return empty($model->telephone) ? "<span class='text-danger'>(Not Set)</span>" : $model->telephone;
                    }
                ],
                'mobile',
                [
                    'attribute'=> 'total_price',
                    'format'=> ['decimal',2]
                ],
                //'created_at:datetime',
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
                    },
                ],
                //'updated_at:datetime',
                //'updated_by',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                {preview} {view} {update} {revision} {delete} {cancel} {done}
                            </div>
                        </div>
                    ',
                    'buttons'=>[
                        'view'=> function($url,$model) {
                            if(in_array($model->status, [$model::STATUS_APPROVE, $model::STATUS_DONE, $model::STATUS_CONFIRM])) {
                                return Html::a('View PDF', '#', [
                                    'title' => Yii::t('app', 'View Quotation'),
                                    //'target' => '_blank',
                                    'class'=>'dropdown-item',
                                    'onclick'=>"window.open('".Yii::$app->getUrlManager()->getBaseUrl()."/quotation/document?id=".$model->id."', '_blank')"
                                ]);
                            } else  {
                                return Html::a('View Quotation', ['view', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'View'),
                                    'class'=>'dropdown-item'
                                ]);

                            }
                        },
                        'preview' => function($url, $model) {
                            if($model->status == $model::STATUS_PENDING) {
                                return Html::a('Preview PDF', '#', [
                                    'title' => Yii::t('app', 'View Quotation'),
                                    //'target' => '_blank',
                                    'class'=>'dropdown-item',
                                    'onclick'=>"window.open('".Yii::$app->getUrlManager()->getBaseUrl()."/quotation/generate-pdf?id=".$model->id."&format=raw', '_blank')"
                                ]);

                            }
                        },
                        'update' => function($url,$model) { if($model->status == $model::STATUS_PENDING && Yii::$app->user->can('update-quotation-status')) {
                                return  Html::a('Approve Quotation', ['#'], [
                                    'value' => Yii::$app->urlManager->createUrl(['quotation/update-status', 'id'=>$model->id]),
                                    'class' => 'showModalButton dropdown-item',
                                    'id' => 'BtnModalId',
                                    'data-toggle'=> 'modal',
                                    'data-target'=> '#modal'
                                ]);
                            } else if(Yii::$app->user->can('update-quotation-status') && $model->status == $model::STATUS_DRAFT) {
                                return  Html::a('Edit Quotation', ['item', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Edit'),
                                    'class'=>'dropdown-item'
                                ]);
                            } else if($model->status == $model::STATUS_DRAFT) {
                                return  Html::a('Edit Quotation', ['item', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Edit'),
                                    'class'=>'dropdown-item'
                                ]);
                            }

                        },
                        'revision' => function($url,$model) { 
                            return  Html::a('Create Revision', ['revision', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Revision'),
                                'data-confirm' => 'Are you sure you want to create revision for this?',
                                'class'=>'dropdown-item'
                            ]);

                        },
                        'delete' => function($url,$model) {
                            return Yii::$app->user->can('delete-quotation') ? Html::a('Delete Quotation', ['delete', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Delete'),
                                'data-confirm' => 'Are you sure you want to delete this quotation?',
                                'class'=>'dropdown-item'
                            ]) : "";
                        },
                        'cancel' => function($url,$model) {
                            return $model->status != $model::STATUS_DONE ? Html::a('Cancel Quotation', ['update-cancel', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Cancel'),
                                'data-confirm' => 'Are you sure you want to cancel this quotation?',
                                'class'=>'dropdown-item'
                            ]) : ""; 
                        },
                        'done' => function($url,$model) {
                            return  $model->status == $model::STATUS_APPROVE ? Html::a('Confirm Quotation', ['#'], [
                                'value' => Yii::$app->urlManager->createUrl(['point-collection/create', 'id'=>$model->id]),
                                'class' => 'showModalButton dropdown-item',
                                'id' => 'BtnModalId',
                                'data-toggle'=> 'modal',
                                'data-target'=> '#modal',
                            ]) : "";
                        }
                        /*'view'=> function($url,$model) {
                            return $model->status == $model::STATUS_APPROVE || $model->status == $model::STATUS_DONE ? Html::a('<span class="fas fa-eye"></span>', ['quotation/document', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'View'),
                                'target' => '_blank'
                            ]) : Html::a('<span class="fas fa-eye"></span>', ['view', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'View'),
                            ]);
                        },
                        'update' => function($url,$model) { if($model->status == $model::STATUS_PENDING && Yii::$app->user->can('update-quotation-status')) {
                                return  Html::a('<span class="fas fa-pencil-alt"></span>', ['#'], [
                                    'value' => Yii::$app->urlManager->createUrl(['quotation/update-status', 'id'=>$model->id]),
                                    'class' => 'showModalButton',
                                    'id' => 'BtnModalId',
                                    'data-toggle'=> 'modal',
                                    'data-target'=> '#modal'
                                ]);
                            } else if(Yii::$app->user->can('update-quotation-status') && $model->status == $model::STATUS_DRAFT) {
                                return  Html::a('<span class="fas fa-pencil-alt"></span>', ['item', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Edit')
                                ]);
                            } else if($model->status == $model::STATUS_DRAFT) {
                                return  Html::a('<span class="fas fa-pencil-alt"></span>', ['item', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Edit')
                                ]);
                            }

                        },
                        'revision' => function($url,$model) { 
                            return  Html::a('<span class="fas fa-file"></span>', ['revision', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Revision'),
                                'data-confirm' => 'Are you sure you want to create revision for this?'
                            ]);

                        },
                        'delete' => function($url,$model) {
                            return Yii::$app->user->can('delete-quotation') ? Html::a('<span class="fas fa-trash-alt"></span>', ['delete', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Delete'),
                                'data-confirm' => 'Are you sure you want to delete this quotation?'
                            ]) : "";
                        },
                        'cancel' => function($url,$model) {
                            return $model->status != $model::STATUS_DONE ? Html::a('<span class="fas fa-times-circle text-danger"></span>', ['update-cancel', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Cancel'),
                                'data-confirm' => 'Are you sure you want to cancel this quotation?'
                            ]) : ""; 
                        },*/
                        /*'done' => function($url,$model) {
                            return  $model->status == $model::STATUS_APPROVE ? Html::a('<span class="fas fa-check-circle text-success"></span>', ['#'], [
                                'value' => Yii::$app->urlManager->createUrl(['point-collection/create', 'id'=>$model->id]),
                                'class' => 'showModalButton',
                                'id' => 'BtnModalId',
                                'data-toggle'=> 'modal',
                                'data-target'=> '#modal'
                            ]) : "";
                            //return $model->status == $model::STATUS_APPROVE ? Html::a('<span class="fas fa-check-circle text-success"></span>', ['point-collection/create', 'id'=>$model->id], [
                                //'title' => Yii::t('app', 'Done'),
                                //'data-confirm' => 'Are you sure you want to convert sales this quotation?'
                            //]) : ""; 
                        }*/
                    ]
                ]
            ];

            $quotationAll = wrapPjax(GridView::widget([
                'dataProvider' => $dataProviderAll,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationApprove = wrapPjax(GridView::widget([
                'dataProvider' => $dataProviderApprove,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationDraft = wrapPjax(GridView::widget([
                'dataProvider' => $dataProviderDraft,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationPending = wrapPjax(GridView::widget([
                'dataProvider' => $dataProviderPending,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationConfirm = wrapPjax(GridView::widget([
                'dataProvider' => $dataProviderConfirm,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationDone = wrapPjax(GridView::widget([
                'dataProvider' => $dataProviderDone,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));
            
            echo TabsX::widget([
                'position' => TabsX::POS_ABOVE,
                'align' => TabsX::ALIGN_LEFT,
                'encodeLabels'=>false,
                'enableStickyTabs' => true,
                'stickyTabsOptions' => [
                // 'selectorAttribute' => "data-target",
                    //'backToTop' => true,
                ], 

                'items' => [
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> All ('.$dataProviderAll->getTotalCount().')',
                        'content' => $quotationAll,
            
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Approve ('.$dataProviderApprove->getTotalCount().')',
                        'content' => $quotationApprove,
            
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Pending ('.$dataProviderPending->getTotalCount().')',
                        'content' => $quotationPending,
            
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Draft ('.$dataProviderDraft->getTotalCount().')',
                        'content' => $quotationDraft,
            
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Confirm ('.$dataProviderConfirm->getTotalCount().')',
                        'content' => $quotationConfirm,
            
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Done ('.$dataProviderDone->getTotalCount().')',
                        'content' => $quotationDone,
            
                    ],

                ]
            ]);
 

            ?>
        </div>
        <!-- /.card-body -->
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


</div>
