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

    $this->title = 'Select Products';
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
[
    'label' => 'BRAND',
    'attribute' => 'brand_name',
    'vAlign' => 'middle',
    'width' => '100px',
    'contentOptions'=>[
        'class'=>'text-center'
    ]
],
[
    'attribute'=>'image',
	'width'=>'100px',
    'contentOptions'=>[
        //'style'=>'100px',
    ],
    'content' => function($model) {
        /**  @var \common\models\Product $model */
        return Html::img($model->getImageUrl(), ['style'=> 'width: 100px']);
    },

],
[
    'label' => 'NAME',
    'attribute' => 'name',
    'format'=>'html',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'label'=> 'PRODUCT CODE',
    'attribute' => 'code',
    'vAlign' => 'right',
    'hAlign' => 'right',
    'width' => '100px',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ]
],
[
    'label'=>'Currency',
    'attribute' => 'projectCurrency.currency',
    'vAlign' => 'right',
    'hAlign' => 'right',
    'width' => '100px',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ]
],
[
    'label'=>'SLP',
    'attribute' => 'retail_base_price',
    'vAlign' => 'right',
    'hAlign' => 'right',
    'width' => '210px',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    'footer' => true
],
[
    'class' => 'kartik\grid\CheckboxColumn',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'pageSummary' => '<small>(amounts in $)</small>',
    'pageSummaryOptions' => ['colspan' => 1, 'data-colspan-dir' => 'rtl']
],
];


Pjax::begin( [
    'id' => 'pjax-product-search-grid',
    'timeout' => false, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'GET']]
);

echo GridView::widget([
    'id' => 'product-search-grid',
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
                Html::a('<i class="fas fa-redo"></i>', ['select', 'id'=>$_REQUEST['id'] ?? 0], [
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
        'after'=>Html::a('<i class="fas fa-plus"></i> Add Product', ['/product-component/product-add', 'id' =>$_REQUEST['id'] ?? 0], [
            'id' => 'add-product-search-grid', 
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
        $("#add-product-search-grid").one('click', function(e){
            e.preventDefault();

            var keys = $('#product-search-grid').yiiGridView('getSelectedRows');
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
                data: { 'ProductSearch[id]' : keys.join() },
                traditional: true,
                success: function(data) {
                    if(data.error) {
                        alert(data.message);
                        return;
                    }
                    $("#pjax-product-grid").html(data);
                    //$.pjax.reload({container: '#pjax-product-grid', 'timeout': 5000});
                    swal("Success..","Your product is added successfull!","success");
                }
            });

            return false;
        });

	$(document).on('pjax:complete', '#pjax-product-grid',function(event) {
		// Assuming we're listening for e.g. a 'change' event on `element`

		// Create a new 'change' event
		var event = new Event('change');

		// Dispatch it.
		document.getElementById('product-product_type').dispatchEvent(event);
	});

        $("#reset-product-search-grid").one('click', function(e) {
            e.preventDefault(); 

            var id='#product-search-grid';
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
                    $("#product-search-grid").html(data);
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

    $(document).on('ready pjax:end', function (event) {
        initializeEvent();
    });
JS;

$this->registerJs($js, $this::POS_END);
?>
