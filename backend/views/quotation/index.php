<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use kartik\daterange\DateRangePicker;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quotations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <?= Html::a('Create Quotation', ['create'], ['class' => 'btn btn-success btn-sm']) ?>            
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

            <br/>
            </div>
            
            <br/>

            <?php 

            function insertArrayAtPosition( $array, $insert, $position ) {
                /*
                $array : The initial array i want to modify
                $insert : the new array i want to add, eg array('key' => 'value') or array('value')
                $position : the position where the new array will be inserted into. Please mind that arrays start at 0
                */
                return array_slice($array, 0, $position, TRUE) + $insert + array_slice($array, $position, NULL, TRUE);
            }

            function wrapPjax($grid) {
                ob_start();

                //Pjax::begin(['timeout' => 10000]);
                echo $grid;
                //Pjax::end();
                
                return ob_get_clean();
            }

            $checkBox = 
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'pageSummary' => '<small>(amounts in $)</small>',
                    'pageSummaryOptions' => ['colspan' => 3, 'data-colspan-dir' => 'rtl'],
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        if ($model->doc_type2 != 'combine') {
                            return ['value' => $key];
                        }
                        return ['disabled'=>true];
                    },
                ];

            $columnsWithCheckbox = [
                ['class' => 'yii\grid\SerialColumn'],
                
                $checkBox, 

                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    // uncomment below and comment detail if you need to render via ajax
                    // 'detailUrl' => Url::to(['/site/book-details']),
                    'detail' => function ($model, $key, $index, $column) {
                        return Yii::$app->controller->renderPartial('/quotation/_expand-combine-quotation-row-details', ['model' => $model]);
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => true,
                    'disabled' => function($model, $key, $index, $column) {
                        return $model->doc_type2 != 'combine' || $model->quotation_id > 0;
                    }
                ],
                //'id',
                'doc_no',
                [
                    'label'=> 'Belongs to',
                    'attribute'=> 'quotation_id',
                    'value'=> 'quotation.doc_no',
                    'headerOptions'=>[
                        'class'=>'text-center'
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                    ]
                ],
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
                [
                    'attribute'=> 'person',
                    'headerOptions'=>[
                        'class'=>'text-center',
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                    ]
                ],
                [
                    'attribute'=> 'created_by',
                    'value'=> 'createdBy.username',
                    'headerOptions'=>[
                        'class'=>'text-center'
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                    ]
                ],
                'doc_title',
                /*'email:email',
                [
                    'attribute'=> 'telephone',
                    'headerOptions'=>[
                        'class'=>'text-center'
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                        'style'=>'white-space: nowrap'
                    ],
                    'content'=>function($model) {
                        return empty($model->telephone) ? "<span class='text-danger'>(Not Set)</span>" : $model->telephone;
                    }
                ],
                [
                    'attribute'=> 'mobile',
                    'headerOptions'=>[
                        'class'=>'text-center',
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                        'style'=>'white-space: nowrap'
                    ]
                ],
                */
                [
                    'attribute'=> 'total_price_after_disc',
                    'label'=> 'Total Sales',
                    'format'=> ['decimal',2],
                    'headerOptions'=>[
                        'class'=>'text-right',
                        'style'=>'white-space: normal'
                    ],
                    'contentOptions'=>[
                        'class'=>'text-right'
                    ]
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
                    'headerOptions'=>[
                        'class'=>'text-center'
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                    ]
                ],
                [
                    'attribute'=> 'approved_by',
                    'value'=> 'approvedBy.username',
                    'headerOptions'=>[
                        'class'=>'text-center',
                    ],
                    'contentOptions'=>[
                        'class'=>'text-center',
                    ]
                ],
                //'updated_at:datetime',
                //'updated_by',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '
                        <div class="btn-group" role="group">
                            {view_quotation} {edit_quotation} {delete}
                            <!--<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>-->
                            &nbsp;<span id="btnGroupDrop1" class="fas fa-ellipsis-h" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:4px; cursor: pointer;"></span>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                {view_pdf} {view_analysis} {approve_quotation} {preview} {revision} {duplicate} {cancel} {done}
                            </div>
                        </div>
                    ',
                    'buttons'=>[
                        'view_pdf'=> function($url,$model) {
                            if(in_array($model->status, [$model::STATUS_APPROVE, $model::STATUS_DONE, $model::STATUS_CONFIRM])) {
                                return Html::a('View PDF', '#', [
                                    'title' => Yii::t('app', 'View PDF'),
                                    'class'=>'dropdown-item',
                                    //'target' => '_blank',
                                    'onclick'=>"window.open('".Yii::$app->getUrlManager()->getBaseUrl()."/quotation/document?id=".$model->id."', '_blank')"
                                ]);
                            }
                        },
                        'view_quotation'=> function($url,$model) {
                            $options = [];
                            if(in_array($model->status, [$model::STATUS_APPROVE, $model::STATUS_DONE, $model::STATUS_CONFIRM])) {
                                $options = [
                                    'class'=>'btn-disabled',
                                    'disabled'=>'disabled',
                                ];
                            }

                            return Html::a('&nbsp;<span class="fas fa-eye"></span>', ['view', 'id'=>$model->id], array_merge([
                                'title' => Yii::t('app', 'View Quotation'),
                            ], $options));
                        },
                        'preview' => function($url, $model) {
                            if($model->status == $model::STATUS_PENDING) {
                                return Html::a('Preview PDF', '#', [
                                    'title' => Yii::t('app', 'Preview PDF'),
                                    //'target' => '_blank',
                                    'class'=>'dropdown-item',
                                    'onclick'=>"window.open('".Yii::$app->getUrlManager()->getBaseUrl()."/quotation/generate-pdf?id=".$model->id."&format=raw', '_blank')"
                                ]);

                            }
                        },
                        'approve_quotation' => function($url,$model) { 
                            if($model->status == $model::STATUS_PENDING && Yii::$app->user->can('update-quotation-status')) {
                                return  Html::a('Approve Quotation', ['#'], [
                                    'value' => Yii::$app->urlManager->createUrl(['quotation/update-status', 'id'=>$model->id]),
                                    'class' => 'showModalButton dropdown-item',
                                    'id' => 'BtnModalId',
                                    'data-toggle'=> 'modal',
                                    'data-target'=> '#modal'
                                ]);

                            } 
                        },
                        'view_analysis' => function($url,$model) { 
                            if($model->status == $model::STATUS_APPROVE && Yii::$app->user->can('update-quotation-status')) {
                                return  Html::a('View Analysis', ['#'], [
                                    'value' => Yii::$app->urlManager->createUrl(['quotation/update-status', 'id'=>$model->id]),
                                    'class' => 'showModalButton dropdown-item',
                                    'id' => 'BtnModalId',
                                    'data-toggle'=> 'modal',
                                    'data-target'=> '#modal'
                                ]);

                            } 
                        },
                        'edit_quotation' => function($url,$model) { 
                            $options = [];
                            if(!(Yii::$app->user->can('update-quotation-status') && $model->status == $model::STATUS_DRAFT)) {
                                $options = [
                                    'class'=>'btn-disabled',
                                    'disabled'=>'disabled',
                                ];
                            } 

                            return  $model->doc_type2 == 'combine' ? Html::a('&nbsp;<span class="fas fa-pencil-alt"></span>', ['combine-item', 'id'=>$model->id], array_merge([
                                'title' => Yii::t('app', 'Edit Quotation'),
                            ], $options)) : Html::a('&nbsp;<span class="fas fa-pencil-alt"></span>', ['item', 'id'=>$model->id], array_merge([
                                'title' => Yii::t('app', 'Edit Quotation'),
                            ], $options));
                        },
                        'revision' => function($url,$model) { 
                            return  Html::a('Create Revision', ['revision', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Revision'),
                                'data-confirm' => 'Are you sure you want to create revision for this?',
                                'class'=>'dropdown-item'
                            ]);

                        },
                        'duplicate' => function($url,$model) { 
                            return  Html::a('Duplicate', ['duplicate', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Duplicate Quotation'),
                                'data-confirm' => 'Are you sure you want to create duplicate for this?',
                                'class'=>'dropdown-item'
                            ]);

                        },
                        'delete' => function($url,$model) {
                            return Yii::$app->user->can('delete-quotation') ? Html::a('&nbsp;<span class="fas fa-trash-alt"></span> ', ['delete', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Delete Quotation'),
                                'data-confirm' => 'Are you sure you want to delete this quotation?',
                            ]) : "";
                        },
                        'cancel' => function($url,$model) {
                            return $model->status != $model::STATUS_DONE ? Html::a('Cancel', ['update-cancel', 'id'=>$model->id], [
                                'title' => Yii::t('app', 'Cancel'),
                                'data-confirm' => 'Are you sure you want to cancel this quotation?',
                                'class'=>'dropdown-item'
                            ]) : ""; 
                        },
                        'done' => function($url,$model) {
                            return  $model->status == $model::STATUS_APPROVE ? Html::a('Confirm', ['#'], [
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
                ],
            ];

            $columns = $columnsWithCheckbox;
            
            $deleteQuotation = "<button type=\"button\" class=\"btn btn-danger btn-sm\" style=\"float: right\" onclick='clickDeleteQuotation()'>Bulk Delete</button>";

            //unset($columns[1]);
            $quotationExportAll = ExportMenu::widget([
                'dataProvider' => $dataProviderAll,
                'columns' => $columns,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ]);


            $quotationAll = $quotationExportAll . $deleteQuotation . wrapPjax(GridView::widget([
                'id'=> 'kv-grid-view-all',
                'dataProvider' => $dataProviderAll,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $combineQuotation = "<button type=\"button\" class=\"btn btn-warning btn-sm\" style=\"float: right\" onclick='clickCombineQuotation()'>Combine Quotation</button>";
            
            
            $quotationExportApprove = ExportMenu::widget([
                'dataProvider' => $dataProviderApprove,
                'columns' => $columns,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ]);

            $quotationApprove = $quotationExportApprove . $combineQuotation . $deleteQuotation . wrapPjax(GridView::widget([
                'id'=> 'kv-grid-view-approve',
                'dataProvider' => $dataProviderApprove,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columnsWithCheckbox
            ]));

            $quotationExportDraft = ExportMenu::widget([
                'dataProvider' => $dataProviderDraft,
                'columns' => $columns,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ]);

            $quotationDraft = $quotationExportDraft . $deleteQuotation . wrapPjax(GridView::widget([
                'id'=> 'kv-grid-view-draft',
                'dataProvider' => $dataProviderDraft,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationExportPending = ExportMenu::widget([
                'dataProvider' => $dataProviderPending,
                'columns' => $columns,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ]);

        $quotationPending = $quotationExportPending . $deleteQuotation . wrapPjax(GridView::widget([
                'id'=> 'kv-grid-view-pending',
                'dataProvider' => $dataProviderPending,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationExportConfirm = ExportMenu::widget([
                'dataProvider' => $dataProviderConfirm,
                'columns' => $columns,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ]);

            $quotationConfirm = $quotationExportConfirm . $deleteQuotation . wrapPjax(GridView::widget([
                'id'=> 'kv-grid-view-confirm',
                'dataProvider' => $dataProviderConfirm,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm table-responsive'],
                'columns' => $columns
            ]));

            $quotationExportDone = ExportMenu::widget([
                'dataProvider' => $dataProviderDone,
                'columns' => $columns,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ]);

            $quotationDone = $quotationExportDone . $deleteQuotation . wrapPjax(GridView::widget([
                'id'=> 'kv-grid-view-done',
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
            'titleOptions'=> ['style'=>'font-size: 26px;'],
	    //'header'=>'<h1>Quotation</h1>',
            'id' => 'modal',
            'size' => 'modal-xl',
        ]);

        echo "<div id='modalContent'><div class='spinner-border' role='status'> <span class='sr-only'>Loading...</span> </div></div>";

        Modal::end();
    
    ?>


</div>
<?php 
$js = <<<JS
function clickCombineQuotation() {
    var selectedRows = $("#kv-grid-view-approve").yiiGridView("getSelectedRows");
    var keysAll = selectedRows.length; 
    if(keysAll>0) {
        window.location.href='create?type=combine&selectedQuotation='+selectedRows;
        return;
    }
    alert("No rows selected to combine.");

}

function clickDeleteQuotation() {
     var keys1 = $("#kv-grid-view-all").yiiGridView("getSelectedRows");
     var keys2 = $("#kv-grid-view-approve").yiiGridView("getSelectedRows");
     var keys3 = $("#kv-grid-view-pending").yiiGridView("getSelectedRows");
     var keys4 = $("#kv-grid-view-draft").yiiGridView("getSelectedRows");
     var keys5 = $("#kv-grid-view-confirm").yiiGridView("getSelectedRows");
     var keys6 = $("#kv-grid-view-done").yiiGridView("getSelectedRows");
     
     if(keys1 == "" && keys2 == "" && keys3 == "" && keys4 == "" && keys5 == "" && keys6 == "") {
        alert("Please select more than one quotation!");
        return false;
     }
     
     var keys = "";
     
     if(keys1.length>0) {
         keys = keys1;
     } else if (keys2.length>0) {
         keys = keys2;
     } else if (keys3.length>0) {
         keys = keys3;
     } else if (keys4.length>0) {
         keys = keys4;
     } else  if (keys5.length>0) {
         keys = keys5;
     } else if (keys6.length>0) {
         keys = keys6;
     }
     
     
    if(keys) {
        $.ajax({
            type: 'POST',
            url: 'delete-all',
            data: { 'QuotationSearch[id]' : keys.join() },
            traditional: true,
            success: function(data) {
                if(data.error) {
                    alert(data.message);
                    return;
                }
                //$("#product-grid-container").html(data);
                //$.pjax.reload({container: '#pjax-quotation-grid', 'timeout': 5000});
                alert("Your quotation is deleted successfully!");
                location.reload();
            }
        });
    }
    
         
        
    
    
}
JS;

$this->registerJs($js, $this::POS_END);
?>