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

    $this->title = 'Selected Products';
    $this->params['breadcrumbs'][] = $this->title;

   $bordered =1;
   $striped=1;
   $condensed=1;
   $responsive=1;
   $hover=1;
   $pageSummary=1;
   $heading="<h4>$this->title</h4>";
   $exportConfig=0;

$gridColumns = [
[
    'class'=>'kartik\grid\SerialColumn',
    'contentOptions'=>['class'=>'kartik-sheet-style'],
    'width'=>'36px',
    'pageSummary'=>'Total',
    'pageSummaryOptions' => ['colspan' => 4],
    'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
[
    'attribute' => 'name',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'attribute' => 'brand_name',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'attribute' => 'code',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'attribute' => 'retail_base_price',
    'vAlign' => 'middle',
    'width' => '210px',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    'footer' => true
],
[
    'class' => 'kartik\grid\ActionColumn',
    'urlCreator' => function($action, $model, $key, $index) { return '#'; },
    'deleteOptions' => ['title' => 'This will launch the book delete action. Disabled for this demo!', 'data-toggle' => 'tooltip'],
    'headerOptions' => ['class' => 'kartik-sheet-style'],
]
];

echo GridView::widget([
    'id' => 'product-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
        'content' =>
            Html::button('<i class="fas fa-plus"></i> Add Product', [
                'value' => Yii::$app->urlManager->createUrl('/product/select'),
                'class' => 'btn btn-success showModalButton',
                'id' => 'BtnModalId',
                'data-toggle'=> 'modal',
                'data-target'=> '#modal'
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
    'showPageSummary' => $pageSummary,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $heading,
        'after'=>Html::button('<i class="fas fa-save"></i> Save', ['id' => 'save-product-btn', 'class' => 'btn btn-info float-right']),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products'
]);

Alert::widget();

$js = <<<JS
    $(document).on('click', '#save-product-btn', function(){
        var keys = $('#product-grid').yiiGridView('getSelectedRows');
        if(keys=="") {
            swal("Oops..","Please select products before saving!","error");
        }
    });

    $(document).on('click', '.showModalButton', function(){
         //check if the modal is open. if it's open just reload content not whole modal
        //also this allows you to nest buttons inside of modals to reload the content it is in
        //the if else are intentionally separated instead of put into a function to get the 
        //button since it is using a class not an #id so there are many of them and we need
        //to ensure we get the right button and content. 
        if ($('#modal').data('bs.modal').isShown) {
            $('#modal').find('#modalContent')
                    .load($(this).attr('value'));
            //dynamiclly set the header for the modal
            //document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        } else {
            //if modal isn't open; open it and load content
            $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));
             //dynamiclly set the header for the modal
            //document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        }
    });
JS;

$this->registerJs($js, $this::POS_END);

?>