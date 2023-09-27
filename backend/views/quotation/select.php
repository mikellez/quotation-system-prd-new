<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use backend\components\Alert;

?>

<?php

    $this->title = 'Select Quotations';
    $this->params['breadcrumbs'][] = $this->title;

   $bordered =1;
   $striped=1;
   $condensed=1;
   $responsive=1;
   $hover=1;
   $pageSummary=0;
   $heading='';
   $exportConfig=0;

$gridColumns = [
[
    'class'=>'kartik\grid\SerialColumn',
    'contentOptions'=>['class'=>'kartik-sheet-style'],
    'width'=>'36px',
    'pageSummary'=>'Total',
    'pageSummaryOptions' => ['colspan' => 4],
    //'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
/*[
    'class' => 'kartik\grid\ExpandRowColumn',
    'width' => '50px',
    'value' => function ($model, $key, $index, $column) {
        return $model->product_type == 'service_package' ? GridView::ROW_COLLAPSED : null;
    },
    // uncomment below and comment detail if you need to render via ajax
    // 'detailUrl' => Url::to(['/site/book-details']),
    'detail' => function ($model, $key, $index, $column) {
        return Yii::$app->controller->renderPartial('/product/_expand-row-details', ['model' => $model]);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'expandOneOnly' => true
],*/
[
    'label' => 'Doc No',
    'attribute' => 'doc_no',
    'vAlign' => 'left',
    'hAlign' => 'left',
    'width' => '100px',
    'contentOptions'=>[
        //'class'=>'text-center'
    ]
],
[
    'class' => 'kartik\grid\CheckboxColumn',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'pageSummary' => '<small>(amounts in $)</small>',
    'pageSummaryOptions' => ['colspan' => 1, 'data-colspan-dir' => 'rtl']
],
];


Pjax::begin( [
    'id' => 'pjax-quotation-search-grid',
    'timeout' => false, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'GET']]
);

echo GridView::widget([
    'id' => 'quotation-search-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    'toolbar' =>  [
        [
            'content' =>
                Html::a('<i class="fas fa-redo"></i>', ['select', 'id'=>$_REQUEST['id']], [
                    'id' => 'reset-product-search-grid',
                    'class' => 'btn btn-outline-secondary',
                    'title'=>'Reset Grid',
                    'data-pjax' => "#pjax-product-search-grid", 
                ]), 
            'options' => ['class' => 'btn-group mr-2']
        ]
    ],
    // set your toolbar
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
        'after'=>Html::a('<i class="fas fa-plus"></i> Add Quotation', ['/quotation/quotation-add', 'id' => $_REQUEST['id']], [
            'id' => 'add-quotation-search-grid', 
            'class' => 'btn btn-info btn-sm float-right',
            'data-pjax' => "#pjax-product-grid", 
        ]),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products'
]);

Pjax::end();

Alert::begin();
?>

<?php
$url = Yii::$app->urlManager->createUrl(['item', 'id'=>Yii::$app->request->getBodyParam('id')]);

$js = <<<JS

    function initializeEvent() {
	$('#add-quotation-search-grid').off();	
	$('#reset-quotation-search-grid').off();	

        $("#add-quotation-search-grid").one('click', function(e){
            e.preventDefault();

            var keys = $('#quotation-search-grid').yiiGridView('getSelectedRows');
            if(keys=="") {
                swal("Oops..","Please select more than one product!","error");
                return false;
            }
            /*$.pjax({
                type: 'POST',
                url: this.href,
                data: { 'ProductSearch[id]' : keys.join() },
                container: '#pjax-product-search-grid',
            });*/

            $.ajax({
                type: 'POST',
                url: this.href,
                data: { 'QuotationSearch[id]' : keys.join() },
                traditional: true,
                success: function(data) {
                    if(data.error) {
                        alert(data.message);
                        return;
                    }
                    //$("#product-grid-container").html(data);
                    $.pjax.reload({container: '#pjax-quotation-grid', 'timeout': 5000});
                    swal("Success..","Your quotation is added successfull!","success");
                }
            });

            return false;
        });

        $("#reset-quotation-search-grid").one('click', function(e) {
            e.preventDefault(); 

            var id='#quotation-search-grid';
            var inputSelector=id+'-filters input, '+id+'-filters select';
            $(inputSelector).each( function(i,o) {
                    $(o).val('');
            });
            var data=$.param($(inputSelector));
            $.ajax({
                type: 'POST',
                url: this.href,
                traditional: true,
                success: function(data) {
                    $("#quotation-search-grid").html(data);
                }
            });
            /*$.pjax({
                type: 'POST',
                url: this.href,
                container: '#pjax-product-search-grid',
            });*/

            return false;
        });

    }

    initializeEvent();

    $(document).on('pjax:end', function (event) {
        initializeEvent();
    });
JS;

$this->registerJs($js, $this::POS_END);
?>
