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
    'width'=>'36px',
    //'pageSummary'=>'Total',
    //'pageSummaryOptions' => ['colspan' => 4],
    //'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
[
    'attribute' => 'name',
	'filter' => false,
	'enableSorting' => false,
    //'vAlign' => 'middle',
    'width' => '210px',
],
[
    'attribute' => 'brand_name',
    'header' => 'Brand',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center',
        'style'=>'white-space: nowrap'
    ],
	'filter' => false,
	'enableSorting' => false,
    //'vAlign' => 'middle',
    'width' => '100px',
],
[
    //'label' => 'PRODUCT PICTURES',
    'attribute'=>'image',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions' => [
        'style'=>'width:100px'
    ],
	'filter' => false,
	'enableSorting' => false,
    'content' => function($model) {
        /**  @var \common\models\Product $model */
        return Html::img($model->getImageUrl(), ['style'=> 'width: 100%']);
    },

],
[
    'attribute' => 'code',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ],
	'filter' => false,
	'enableSorting' => false,
    //'vAlign' => 'middle',
    'width' => '100px',
],
[
    'attribute' => 'quantity', 
    'label' => 'Qty', 
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ],
	'filter' => false,
	'enableSorting' => false,
    'hAlign' => 'right', 
    //'vAlign' => 'middle',
    'width' => '7%',
    'format' => ['integer'],
    //'pageSummary' => true
],
[
    'attribute' => 'retail_base_price',
    'headerOptions'=>[
        'class'=>'text-right',
        'style'=>'white-space: normal'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ],
	'filter' => false,
	'enableSorting' => false,
    //'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true,
    //'footer' => true
],
[
    'header'=>'Threshold',
    'content' => function($model) {
        /**  @var \common\models\Product $model */
        return $model->retail_base_price !=0 &&  ($model->discount > $model->threshold_discount || 100 * ($model->retail_base_price - ($model->retail_base_price - $model->discountrm)) / $model->retail_base_price > $model->threshold_discount) ? "<span class='fas fa-times-circle text-danger'></span>" : "<span class='fas fa-check-circle text-success'></span>";
    },
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ]
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discount', 
	'label' => 'Request Disc(%)',
    'headerOptions'=> [
        'style'=>'white-space:normal'
    ],
    'readonly' => $quotationModel->status != $quotationModel::STATUS_PENDING, // do not allow editing of inactive records
    'editableOptions' => [
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax({
					container: '#pjax-product-status', 
					url: '".Yii::$app->urlManager->createUrl(['/quotation/update-status', 'id'=>Yii::$app->request->get('id'), 'page'=>Yii::$app->request->get('page')])."',
					type: 'POST'
				});
            }",
        ]
    ],
	'enableSorting' => false,
    'hAlign' => 'right', 
    //'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discountrm', 
	'label' => 'Disc(RM)/ unit',
    'headerOptions'=> [
        'style'=>'white-space:normal'
    ],
    'readonly' => $quotationModel->status != $quotationModel::STATUS_PENDING, // do not allow editing of inactive records
    'editableOptions' => [
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax({
					container: '#pjax-product-status', 
					url: '".Yii::$app->urlManager->createUrl(['/quotation/update-status', 'id'=>Yii::$app->request->get('id'), 'page'=>Yii::$app->request->get('page')])."',
					type: 'POST'
				});
            }",
        ]
    ],
	'enableSorting' => false,
    'hAlign' => 'right', 
    //'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'admin_discount', 
	'label' => 'Disc.(%)',
	'enableSorting' => false,
	'filter' => false,
    'readonly' => $quotationModel->status != $quotationModel::STATUS_PENDING, // do not allow editing of inactive records
    /*'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },*/
    'editableOptions' => [
        'header' => 'Discount', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax({
					container: '#pjax-product-status', 
					url: '".Yii::$app->urlManager->createUrl(['/quotation/update-status', 'id'=>Yii::$app->request->get('id'), 'page'=>Yii::$app->request->get('page')])."',
					type: 'POST'
				});
            }",
        ]
    ],
    'hAlign' => 'right', 
    //'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true
],
[
    'attribute' => 'price_after_disc',
    'header' => 'Selling Price (RM)',
    'headerOptions'=>[
        'class'=>'text-right'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ],
	'filter' => false,
	'enableSorting' => false,
    'vAlign' => 'top',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true,
    //'footer' => true
],
/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Selling Price (RM)', 
    'vAlign' => 'top',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
		$qty = $widget->col(5, $p);
		$price = $widget->col(6, $p);
		$amt = $qty * $price;
		$discount1 = $widget->col(8, $p)/100;
		$discountrm = $widget->col(9, $p);
		$discount2 = $discountrm > 0 ? $discountrm*$qty : 0;
		//$discount2 = $discountrm > 0 ? ($amt - ($discountrm))/$amt : 0;
		$admindiscount = $widget->col(10, $p)/100;
        return  $amt - ($amt * $discount1) - ($amt * $admindiscount) - $discount2;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'white-space: normal'],
    'hAlign' => 'right', 
    'width' => '7%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    //'pageSummary' => true,
    //'footer' => true
],*/
[
    'attribute' => 'standard_costing',
    'header' => 'Cost',
    'headerOptions'=>[
        'class'=>'text-right'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ],
	'filter' => false,
	'enableSorting' => false,
    //'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true,
    //'footer' => true
],
[
    'attribute' => 'margin',
    'header' => 'Gross Profit Margin (%)',
    'headerOptions'=>[
        'class'=>'text-right'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ],
	'filter' => false,
	'enableSorting' => false,
    'vAlign' => 'top',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true,
    //'footer' => true
],
/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Gross Profit Margin (%)', 
    'vAlign' => 'top',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(5, $p);
        $price = $widget->col(6, $p);
        $amt = $qty * $price;
	$discount1 = $widget->col(8, $p)/100;
	$discountrm = $widget->col(9, $p);
	$discount2 = $discountrm > 0 ? $discountrm*$qty : 0;
	$admindiscount = $widget->col(10, $p)/100;
	$standard_costing = $widget->col(12, $p);
	$selling_price_after_disc = $amt - ($amt * $discount1) - $discount2 - ($amt * $admindiscount); 

	return (($selling_price_after_disc-$standard_costing)/$selling_price_after_disc)*100;

    },
    'headerOptions' => ['class' => 'kartik-sheet-style', 'style'=>'white-space: normal'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'autoFooter'=>false,
],*/
];
echo '<label for="quotation-products">Products</label>';

Pjax::begin( [
    'id' => 'pjax-product-status',
    'timeout' => false, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'POST']]
);

echo GridView::widget([
    'id' => 'product-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
       'content' =>false
    ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // parameters from the demo form
    'bordered' => $bordered,
    'striped' => $striped,
    'condensed' => $condensed,
    'responsive' => $responsive,
    'hover' => $hover,
    //'showPageSummary' => $pageSummary,
    'showPageSummary' => false,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => false,
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products',
    'showFooter'=>true,
    'beforeFooter'=>"
	<tr class='table-warning kv-page-summary'>
		<td colspan='6'>Total</td>
		<td align='right'>".number_format($quotationModel->total_retail_base_price,2)."</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>N/A</td>
		<td align='right'>".number_format($quotationModel->total_price_after_disc,2)."</td>
		<td align='right'>".number_format($quotationModel->total_cost,2)."</td>
		<td align='right'>".number_format($quotationModel->total_margin,2)."</td>
	</tr>",
]);

Pjax::end();

Alert::widget();

$total = $dataProvider->getTotalCount();
$id = Yii::$app->request->get('id');

$js = <<<JS
JS;

$this->registerJs($js, $this::POS_END);

?>
