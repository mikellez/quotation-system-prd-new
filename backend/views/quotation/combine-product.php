<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use backend\components\Alert;

?>

<?php

    $this->title = 'Selected Quotations';
    $this->params['breadcrumbs'][] = $this->title;

   $bordered =1;
   $striped=0;
   $condensed=1;
   $responsive=1;
   $hover=0;
   $pageSummary=1;
   $heading="<h4>$this->title</h4>";
   $exportConfig=0;

$gridColumns = [
[
    'class'=>'kartik\grid\SerialColumn',
    'contentOptions'=>['class'=>'kartik-sheet-style'],
    'width'=>'5%',
    //'pageSummary'=>'Total',
    //'pageSummaryOptions' => ['colspan' => 4],
    'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
[
    'class' => 'kartik\grid\ExpandRowColumn',
    'width' => '50px',
    'value' => function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
    },
    // uncomment below and comment detail if you need to render via ajax
    // 'detailUrl' => Url::to(['/site/book-details']),
    'detail' => function ($model, $key, $index, $column) {
        return Yii::$app->controller->renderPartial('/quotation/_expand-combine-row-details', ['model' => $model]);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'expandOneOnly' => true
],
[
    'attribute' => 'linkQuotation.doc_no',
    'vAlign' => 'middle',
    'width' => '30%',
],
/*[
    'attribute' => 'linkQuotation.doc_name',
    'vAlign' => 'middle',
    'width' => '30%',
],*/
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'doc_name', 
    'value' => function($model){ return $model->docName; },
    'editableOptions' => [
        'header' => 'Other Categories', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
        'options' => [
           //'editableValueOptions'=>['type'=>'text']
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#quotation-grid-container').css('opacity', '0.5');
                $('#quotation-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#pjax-quotation-grid', 'timeout': 5000});
            }",
        ]
    ],
    'hAlign' => 'left', 
    'vAlign' => 'middle',
    'width' => '30%',
],
[
    'header'=>'Total Price',
    'attribute' => 'linkQuotation.total_price_after_disc',
    'vAlign' => 'right',
    'hAlign' => 'right',
    'format' => ['decimal', 2],
    'width' => '10%',
    //'pageSummary' => true,
    //'footer' => true
],
/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Total Proposal Amount', 
    'attribute' => 'linkQuotation.total_price_after_disc', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');

        return  $widget->col(3, $p);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '7%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'footer' => true
],*/
[
    'class' => 'kartik\grid\ActionColumn',
    'template' => '{delete}',
    'width'=> "5%",
    'buttons' => [
        'update' => function($url, $model){
            return  Html::a('<span class="fas fa-pencil-alt"></span>', ['/quotation/item', 'id'=>$model->link_quotation_id], [
                'title' => Yii::t('app', 'View')
            ]);

        },
        'delete' => function ($url, $model) {
            return Html::a('<span class="fas fa-trash-alt"></span>', ['#'], [
                'class' => 'pjax-quotation-delete-link',
                'delete-url' => $url,
                'pjax-container' => 'pjax-quotation-grid'
            ]);
        }
    ],
    'urlCreator' => function($action, $model, $key, $index) { 
        if($action === "delete") {
            $url =Yii::$app->urlManager->createUrl(['/quotation/item-delete','id' => $model->id]);
            return $url;
        }
    },
    /*'viewOptions' => ['label'=> false, 'icon' => false],
    'updateOptions' => ['label'=>false, 'icon' => false],
    'deleteOptions' => ['title' => 'Delete quotation', 'data-toggle' => 'tooltip'],*/
    'headerOptions' => ['class' => 'kartik-sheet-style'],
]
];

echo GridView::widget([
    'id' => 'quotation-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'showFooter' => true,
    'afterFooter'=>'<tr class="table-warning kv-page-summary"><td colspan="4" align="right">Grand Total (RM)</td><td align="right">'.number_format($quotationModel->total_price,2).'</td><td>&nbsp;</td></tr>',
    'footerRowOptions' => ['class' => 'table-warning kv-page-summary', 'style'=>'display:none;'],
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
        'content' =>
            /*Html::button('<i class="fas fa-plus"></i> Add Quotation', [
                'value' => Yii::$app->urlManager->createUrl(['/quotation-group/select', 'id' => Yii::$app->request->get('id')]),
                'class' => 'btn btn-success btn-sm showModalButton',
                'id' => 'BtnModalAddId',
                'data-toggle'=> 'modal',
                'data-target'=> '#modal'
            ]).'&nbsp;'.*/
            Html::button('<i class="fas fa-plus"></i> Add Quotation', [
                'value' => Yii::$app->urlManager->createUrl(['/quotation/select', 'id' => Yii::$app->request->get('id')]),
                'class' => 'btn btn-success btn-sm showModalButton',
                'id' => 'BtnModalId',
                'data-toggle'=> 'modal',
                'data-target'=> '#modal'
            ]). '&nbsp;'.
            Html::a('<i class="fas fa-edit"></i> Edit Header', [ '/quotation/update', 'id' => Yii::$app->request->get('id')  ],[
                'class' => 'btn btn-warning btn-sm showModalButton',
                'id' => 'BtnModalAddId',
            ]),
            'options' => ['class' => 'btn-group mr-2']
        ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // parameters from the demo form
    'bordered' => $bordered,
    'striped' => $striped,
    'condensed' => $condensed,
    'responsive' => $responsive,
    'hover' => $hover,
    //'showPageSummary' => $pageSummary,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $heading,
        'after'=>Html::button('<i class="fas fa-save"></i> Generate Quotation', ['id' => 'generate-quotation-btn', 'class' => 'btn btn-info btn-sm float-right']).Html::button('<i class="fas fa-file"></i> Preview Quotation', ['id' => 'preview-quotation-btn', 'class' => 'btn btn-default btn-sm float-right mr-1']),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'quotation',
    'itemLabelPlural' => 'quotations'
]);

Alert::widget();

$total = $dataProvider->getTotalCount();
$id = Yii::$app->request->get('id');

$js = <<<JS
    /*$(document).on('click', '#generate-quotation-btn', function(){
        swal("Are you sure?","You will be generating quotation..","warning")
        .then(okay => {
            if (okay) {

                $.ajax({
                    url: 'generate?id=$id',
                    type: 'post',
                    success: function(data) {
                        if(data.success) {
                            swal("Success..","Quotation generated, ref no: "+data.model.doc_no,"success")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = 'index';
                                    window.open('document?id='+data.model.id, '_blank');
                                }
                            });
                        } else {
                            swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+data.model.doc_no,"error")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = "index";
                                }
                            });
                        }
                    }
                });
            }
        });
    });*/
    $(document).on('click', '#generate-quotation-btn', function(){
        swal({
            title: 'Are you sure?',
            html: "You will be generating quotation, please allow pop-ups for this site. For more information, visit this <a href='https://support.google.com/chrome/answer/95472?hl=en&co=GENIE.Platform%3DDesktop'>link.</a>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                const content = swal.getContainer()
                if (content) {
                    const swalTitle = content.querySelector('.swal2-title');
                    const swalContent = content.querySelector('.swal2-content');
                    const swalCancel = content.querySelector('.swal2-cancel');

                    if (swalTitle) {
                        swalTitle.textContent = 'Please wait..';
                    }
                    if (swalContent) {
                        swalContent.textContent = 'Do not close this modal, system is generating quotation..';
                    }
                    if (swalCancel) {
                        swalCancel.remove();
                    }
                }
                return fetch('generate?id=$id')
                .then(response => {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
            },
            allowOutsideClick: () => !swal.isLoading()
            }).then((response) => {
                console.log(response);
            if (response.value.success) {
                swal("Success..","Quotation generated, ref no: "+response.value.model.doc_no,"success")
                .then(okay => {
                    if (okay) {
                        window.location.href = 'index';
                        window.open('document?id='+response.value.model.id, '_blank');
                    }
                });
            } else {
                swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+response.value.model.doc_no,"error")
                .then(okay => {
                    if (okay) {
                        window.location.href = "index";
                    }
                });
            }
        })
        /*swal("Are you sure?","You will be generating quotation..","warning")
        .then(function() {
            console.log(this.swal);
        })
        .then(okay => {
            if (okay) {
                var keys = $('#product-grid').yiiGridView('getSelectedRows');
                if(keys=="") {
                    swal("Oops..","Please select products before saving!","error");
                    return false;
                }

                $.ajax({
                    url: 'generate?id=$id',
                    type: 'post',
                    success: function(data) {
                        if(data.success) {
                            swal("Success..","Quotation generated, ref no: "+data.model.doc_no,"success")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = 'index';
                                    window.open('document?id='+data.model.id, '_blank');
                                }
                            });
                        } else {
                            swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+data.model.doc_no,"error")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = "index";
                                }
                            });
                        }
                    }
                });
            }
        });*/
        //var keys = $('#product-grid').yiiGridView('getSelectedRows');
    });

    $('.pjax-quotation-delete-link').one('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('delete-url');
        var pjaxContainer = $(this).attr('pjax-container');
        var result = confirm('Delete this item, are you sure?');                                
        if(result) {
            $.ajax({
                url: deleteUrl,
                type: 'post',
                error: function(xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            }).done(function(data) {
                $('#quotation-grid-container').css('opacity', '0.5');
                $('#quotation-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#' + pjaxContainer, 'timeout': 5000});
            });
        }
    });

JS;

$this->registerJs($js, $this::POS_END);

?>