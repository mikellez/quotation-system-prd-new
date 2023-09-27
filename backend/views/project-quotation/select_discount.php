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
    'pageSummary'=>'Total',
    'pageSummaryOptions' => ['colspan' => 2],
    //'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style'],
    'footer'=> 'Accumulate Discount Rate %',
    'footerOptions'=>['colspan'=>2]
],
[
    'label' => 'PRODUCT NAME',
    'attribute' => 'name',
    'vAlign' => 'middle',
    'width' => '25%',
],
[
    'label' => 'Total Qty (PCS)',
    'attribute' => 'quantity',
    'hAlign' => 'right',
    'width' => '10%',
    //'format' => ['decimal', 2],
    'pageSummary' => true,
    //'footer' => true
],
[
    'label' => 'Unit Price (RM)',
    'attribute' => '',
    'hAlign' => 'right',
    'width' => '10%',
    'hidden' => true,
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'project_base_price', 
    'label' => 'List Price',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'List Price', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $.pjax({
					container: '#pjax-product-discount-grid', 
					url: '".Yii::$app->urlManager->createUrl(['/project-quotation/select-discount', 'id'=>Yii::$app->request->get('id')])."',
					type: 'POST',
                    async: false
				});
                $.pjax({
                    container: '#pjax-product-grid', 
                    url: '".Yii::$app->urlManager->createUrl(['/project-quotation/item', 'id'=>Yii::$app->request->get('id')])."',
                    type: 'POST',
                    async: false
                });
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'project_threshold_discount', 
    'label' => 'Discount(%)',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'Discount(%)', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $.pjax({
					container: '#pjax-product-discount-grid', 
					url: '".Yii::$app->urlManager->createUrl(['/project-quotation/select-discount', 'id'=>Yii::$app->request->get('id')])."',
					type: 'POST',
                    async: false
				});
                $.pjax({
                    container: '#pjax-product-grid', 
                    url: '".Yii::$app->urlManager->createUrl(['/project-quotation/item', 'id'=>Yii::$app->request->get('id')])."',
                    type: 'POST',
                    async: false
                });
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Selling Price (RM)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(4, $p);
		$discount = $widget->col(5, $p)/100;
        $cost = $price * (1 - $discount);
        $brand = $model->brand;
        $brand_sales_tax = $model->brand ? $model->brand->sales_tax/100 : 1; 
        if(!$model->auto_calc) {
            $brand_sales_tax = 1;
        }
        $brand_mark_up = $model->brand ? $model->brand->mark_up/100 : 1;
        $sales_tax = $cost * $brand_sales_tax;
        $nett_cost = $cost + $sales_tax;
        $less = $model->brand ? $model->brand->less/100 : 1;
        $selling_price = ($nett_cost / ( 1 - $brand_mark_up)) * (1 - $less);

        return  $selling_price;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    //'footer'=>'10'
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Cost', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(4, $p);
		$discount = $widget->col(5, $p)/100;

        return $price * (1 - $discount); 
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    //'footer'=>'10'
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Sales Tax', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(4, $p);
		$discount = $widget->col(5, $p)/100;
        $cost = $price * (1 - $discount);
        $brand = $model->brand;
        $brand_sales_tax = $model->brand ? $model->brand->sales_tax/100 : 0;
        if(!$model->auto_calc) {
            $brand_sales_tax = 0;
        }

        return  $cost * $brand_sales_tax;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    //'footer'=>'10'
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Nett Cost', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(4, $p);
		$discount = $widget->col(5, $p)/100;
        $cost = $price * (1 - $discount);
        $brand = $model->brand;
        $brand_sales_tax = $model->brand ? $model->brand->sales_tax/100 : 1;
        if(!$model->auto_calc) {
            $brand_sales_tax = 1;
        }
        $sales_tax = $cost * $brand_sales_tax;
        $brand_cost = $model->brand ? $model->brand->cost/100 : 1;
        $nett_cost = ($cost + $sales_tax) * (1 - $brand_cost);

        return $nett_cost;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    //'footer'=>'10'
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Total Cost', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(4, $p);
		$discount = $widget->col(5, $p)/100;
        $cost = $price * (1 - $discount);
        $brand = $model->brand;
        $brand_sales_tax = $model->brand ? $model->brand->sales_tax/100 : 1;
        if(!$model->auto_calc) {
            $brand_sales_tax = 1;
        }
        $sales_tax = $cost * $brand_sales_tax;
        $nett_cost = $cost + $sales_tax;
        $qty = $widget->col(2, $p)/100;

        return  $qty * $nett_cost;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    //'footer'=>'10'
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Amount (RM)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(4, $p);
		$discount = $widget->col(5, $p)/100;
        $cost = $price * (1 - $discount);
        $brand = $model->brand;
        $brand_sales_tax = $model->brand ? $model->brand->sales_tax/100 : 1;
        if(!$model->auto_calc) {
            $brand_sales_tax = 1;
        }
        $brand_mark_up = $model->brand ? $model->brand->mark_up/100 : 1;
        $sales_tax = $cost * $brand_sales_tax;
        $nett_cost = $cost + $sales_tax;
        $selling_price = $nett_cost / ( 1 - $brand_mark_up);
        $qty = $widget->col(2, $p);

        return $qty * $selling_price;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    //'footer'=>'10'
],
[
    'class' => 'kartik\grid\CheckboxColumn',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'content'=>function($model, $key){
        $checkedArr = $model->auto_calc ? ['checked'=>'checked'] : [];
        
        return Html::checkbox('selection[]', false, array_merge(['class'=>'kv-row-checkbox', 'value'=>$key, 'onclick'=>''], $checkedArr));
    },
    'pageSummary' => '<small>(on/off sales tax)</small>',
    'pageSummaryOptions' => ['colspan' => 1, 'data-colspan-dir' => 'rtl']
],
];

Pjax::begin( [
    'id' => 'pjax-product-discount-grid',
    'timeout' => 5000, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'POST']]
);

$accumulate_discount_rate = 0;
$total_price = 0;
$total_discount = 0;
$total_discount_value = 0;

foreach($dataProvider->models as $m)
{
    $qty = $m->quantity;
    $price = $m->retail_base_price;
    $discount = $m->discount;
    $amt = $qty*$price;
    $total_discount_value += $amt*$discount;
    $total_price += $amt;
}

$accumulate_discount_rate = $total_price > 0 ? number_format(($total_discount_value/$total_price),2) : 0;

echo GridView::widget([
    'id' => 'product-discount-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
	'options' => [ 'style' => 'table-layout:fixed;' ],
    'showFooter' => true,
    //'afterFooter'=>'<tr class="table-warning kv-page-summary"><td colspan="8" align="right">Accumulate Discount Rate %</td><td align="right">'.$accumulate_discount_rate.'</td></tr>',
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'footerRowOptions' => ['class' => 'table-warning kv-page-summary', 'style'=>'display:none;'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
			'content' =>'',
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
        //'after'=>Html::button('<i class="fas fa-save"></i> Generate Quotation', ['id' => 'generate-quotation-btn', 'class' => 'btn btn-info btn-sm float-right']),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products'
]);

Pjax::end();

Alert::widget();

$total = $dataProvider->getTotalCount();
$id = Yii::$app->request->get('id');

$js = <<<JS

$(".kv-row-checkbox").one("click",function(){
    console.log(this);
    var keys = $('#product-discount-grid').yiiGridView('getSelectedRows');
    var url = "update-auto-calc?id=$id";
    var pjaxContainer = "pjax-product-discount-grid";
    $.ajax({
        url: url,
        data: {'checked': keys.join()},
        type: 'post',
        traditional: true,
        error: function(xhr, status, error) {
            alert('There was an error with your request.' + xhr.responseText);
        },
        success: function(data) {
            //$.pjax.reload({'container': '#' + pjaxContainer});
            $('#product-discount-grid-container').css('opacity', '0.5');
            $('#product-discount-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
            $.pjax({
                container: '#pjax-product-discount-grid', 
                url: "select-discount?id=$id",
                type: 'POST',
                async: false
            });
            $.pjax({
                container: '#pjax-product-grid', 
                url: "item?id=$id",
                type: 'POST',
                async: false
            });
        }
    });
});

$(document).on('ready pjax:end', function (event) {
    $(".kv-row-checkbox").one("click",function(){
        console.log(this);
        var keys = $('#product-discount-grid').yiiGridView('getSelectedRows');
        var url = "update-auto-calc?id=$id";
        var pjaxContainer = "pjax-product-discount-grid";
        $.ajax({
            url: url,
            data: {'checked': keys.join()},
            type: 'post',
            traditional: true,
            error: function(xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            },
            success: function(data) {
                //$.pjax.reload({'container': '#' + pjaxContainer});
                $('#product-discount-grid-container').css('opacity', '0.5');
                $('#product-discount-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax({
                    container: '#pjax-product-discount-grid', 
                    url: "select-discount?id=$id",
                    type: 'POST',
                    async: false
                });
                $.pjax({
                    container: '#pjax-product-grid', 
                    url: "item?id=$id",
                    type: 'POST',
                    async: false
                });
            }
        });
    });
});

JS;

$this->registerJs($js, $this::POS_END);

?>