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
    'label' => 'SLP (RM)',
    'attribute' => 'retail_base_price',
	'label'=> 'RRP (RM)',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    //'footer' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'quantity', 
	'hidden' => true,
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => function($model, $key, $index) {
	return [
		'header' => 'Quantity', 
		'inputType' => \kartik\editable\Editable::INPUT_TEXT,
		'options' => [
		   // 'pluginOptions' => ['min' => 0, 'max' => 5000]
			'id' => "quantity_{$index}"
		],
		'asPopover' => false,
		'pluginEvents'=>[
		    "editableSuccess"=>"function(event, val, form, data) {
			$('#product-grid-container').css('opacity', '0.5');
			$('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
			$.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000});
			$.pjax.reload({'container': '#pjax-product-discount-grid', 'timeout': 5000});
		    }",
		]
	    ];
    },
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '7%',
    'format' => ['integer'],
    'pageSummary' => true,
    'footer'=>false
],
[
    'label' => 'Threshold Discount(%)',
    'attribute' => 'threshold_discount',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discount', 
    'label' => 'Discount(%)',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => function($model, $key, $index) {
	return [
		'header' => 'Discount(%)', 
		'inputType' => \kartik\editable\Editable::INPUT_TEXT,
		'options' => [
		    'pluginOptions' => ['min' => 0, 'max' => 5000],
		    'id' => "discount_{$index}"
		],
		'asPopover' => false,
		'pluginEvents'=>[
		    "editableSuccess"=>"function(event, val, form, data) {
			$.pjax({
						container: '#pjax-product-discount-grid', 
						url: '".Yii::$app->urlManager->createUrl(['/quotation/select-discount', 'id'=>Yii::$app->request->get('id'), 'page'=>Yii::$app->request->get('page')])."',
						type: 'POST',
			    async: false
					});
			$.pjax({
			    container: '#pjax-product-grid', 
			    url: '".Yii::$app->urlManager->createUrl(['/quotation/item', 'id'=>Yii::$app->request->get('id')])."',
			    type: 'POST',
			    async: false
			});
		    }",
		]
	    ];

    },
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discountrm', 
    'label' => 'Discount(RM) per unit',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'Discount(RM) per unit', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $.pjax({
					container: '#pjax-product-discount-grid', 
						url: '".Yii::$app->urlManager->createUrl(['/quotation/select-discount', 'id'=>Yii::$app->request->get('id'), 'page'=>Yii::$app->request->get('page')])."',
					type: 'POST',
                    async: false
				});
                $.pjax({
                    container: '#pjax-product-grid', 
                    url: '".Yii::$app->urlManager->createUrl(['/quotation/item', 'id'=>Yii::$app->request->get('id')])."',
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
    'label' => 'After disc. Selling Price (RM)',
    'attribute' => 'retail_base_price_after_disc',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
],

/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'After disc. Selling Price (RM)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = 1;
        $price = $widget->col(2, $p);
        $amt = $qty * $price;
	$discount1 = $widget->col(5, $p)/100;
	$discountrm = $widget->col(6, $p);
	$discount2 = $discountrm > 0 ? ($amt - ($discountrm))/$amt : 0;

        return  $amt - ($amt * $discount1) - $discountrm;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'width: 50px'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
],*/

[
    'label' => 'Total Selling Price (RM)',
    'attribute' => 'price_after_disc',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
],
/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Total Selling Price (RM)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(3, $p);
        $price = $widget->col(2, $p);
        $amt = $qty * $price;
	$discount1 = $widget->col(5, $p)/100;
	$discountrm = $widget->col(6, $p);
	$discount2 = $discountrm > 0 ? $discountrm*$qty : 0;
	return  $amt - ($amt * $discount1) - $discount2;

    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
],*/

[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Standard Costing (RM)', 
    'vAlign' => 'middle',
    'attribute' => 'standard_costing',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
],

[
    'label' => 'Gross Profit Margin (%)',
    'attribute' => 'margin',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
],

/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Gross Profit Margin (%)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(3, $p);
        $price = $widget->col(2, $p);
        $amt = $qty * $price;
	$discount1 = $widget->col(5, $p)/100;
	$discountrm = $widget->col(6, $p);
	$discount2 = $discountrm > 0 ? $discountrm*$qty : 0;
	$standard_costing = $widget->col(9, $p);
	$selling_price_after_disc = $amt - ($amt * $discount1) - $discount2; 

	return (($selling_price_after_disc-$standard_costing)/$selling_price_after_disc)*100;

    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    //'pageSummary' => true,
    'autoFooter'=>false,
],*/

[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Total Discount Value (RM)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(3, $p);
        $price = $widget->col(2, $p);
        $amt = $qty * $price;
	$discount1 = $widget->col(5, $p)/100;
	$discountrm = $widget->col(6, $p);
	$discount2 = $discountrm > 0 ? ($amt - ($discountrm*$qty))/$amt : 0;
        //return  ($amt * $discount1) + ($discountrm * $qty); 
        return  ($amt * $discount1); 

    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    'footer'=>false
]
/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Accumulate Discount Rate %', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(3, $p);
        $price = $widget->col(2, $p);
        $amt = $qty * $price;
		$discount1 = $widget->col(5, $p)/100;
        $total_discount_value = $amt*$discount1;

        return  ($total_discount_value/$price) * 100;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
]*/
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
$grand_total_price = 0;
$discount = 0;
$total_price_after_disc = 0;
$total_selling_price_after_disc = 0;
$total_standard_costing = 0;
$total_selling_price = 0;

foreach($dataProvider->models as $m)
{
    $qty = intval($m->quantity);
    $price = intval($m->retail_base_price) ?? 0;
    $standard_costing = intval($m->standard_costing)*$qty;
    $discount = intval($m->discount)/100;
    $discountrm = intval($m->discountrm);
    $amt = $qty*$price;
    //$discount2 = $discountrm > 0 ? $discountrm*$qty : 0;
    $discount2 = $discountrm > 0 ? ($amt - ($discountrm))/$amt : 0;
    $total_discount_value += ($amt*$discount) + $discount2;
    $total_price += $price;//number_format($price,2);
    $grand_total_price += $amt;
    $total_price_after_disc += ($amt - ($amt * $discount) - $discountrm);
    $selling_price_after_disc = $amt - ($amt * $discount) - $discount2; 
    $total_selling_price_after_disc += $selling_price_after_disc;
    $total_standard_costing += $standard_costing*$qty;
    $total_discount_value += (($amt * $discount) + ($discountrm * $qty)); 

}

$total_price = $total_price;//number_format($total_price,2);
$total_price_after_disc = $total_price_after_disc;//number_format($total_price_after_disc,2);
$grand_total_price = $total_price_after_disc;
$total_selling_price = $total_selling_price;
$total_standard_costing = $total_standard_costing;
$total_gross_profit_margin = ($total_selling_price_after_disc-$total_standard_costing)/$total_selling_price_after_disc;
$total_discount_value = $total_discount_value;//number_format($total_discount_value,2);

$accumulate_discount_rate = $total_price > 0 ? number_format(($total_discount_value/$total_price*100),2) : 0;
	/*<tr class='table-warning kv-page-summary'>
		<td colspan='2'>Total</td>
		<td align='right'>$total_price</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>$total_price_after_disc</td>
		<td align='right'>$total_selling_price_after_disc</td>
		<td align='right'>$total_standard_costing</td>
		<td align='right'>$total_gross_profit_margin</td>
		<td align='right'>$total_discount_value</td>
	</tr>*/

echo GridView::widget([
    'id' => 'product-discount-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
	'options' => [ 'style' => 'table-layout:fixed;' ],
    'showFooter' => true,
    'beforeFooter'=>"
	<tr class='table-warning kv-page-summary'>
		<td colspan='2'>Total</td>
		<td align='right'>".number_format($quotationModel->total_retail_base_price,2)."</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>".number_format($quotationModel->total_retail_base_price_after_disc,2)."</td>
		<td align='right'>".number_format($quotationModel->total_price_after_disc,2)."</td>
		<td align='right'>".number_format($quotationModel->total_cost,2)."</td>
		<td align='right'>".number_format($quotationModel->total_margin,2)."</td>
		<td align='right'>".number_format(($quotationModel->total_discount_value+$quotationModel->total_discountrm_value+$quotationModel->total_admin_discount_value),2)."</td>
	</tr>",
    'afterFooter'=>'
	<tr class="table-warning kv-page-summary">
		<td colspan="10" align="right">Accumulate Discount Rate %</td>
		<td align="right">'.number_format($quotationModel->accumulate_discount_rate,2).'</td>
	</tr>',
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
    //'showPageSummary' => $pageSummary,
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
JS;

$this->registerJs($js, $this::POS_END);

?>
