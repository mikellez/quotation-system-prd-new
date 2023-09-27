<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\form\ActiveForm;
use backend\components\Alert;

?>

<?php

$this->title = 'VIP Discount Item';
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
    'attribute' => 'name',
    'vAlign' => 'middle',
    'width' => '25%',
],
/*[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discount', 
    'label' => 'Discount(%)',
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'Discount(%)', 
        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $.pjax({
					container: '#pjax-item-discount-grid', 
					url: '".Yii::$app->urlManager->createUrl(['/quotation-group/select-discount', 'id'=>Yii::$app->request->get('id')])."',
					type: 'POST',
                    async: false
				});
                $.pjax({
                    container: '#pjax-product-grid', 
                    url: '".Yii::$app->urlManager->createUrl(['/quotation-group/item', 'id'=>Yii::$app->request->get('id')])."',
                    type: 'POST',
                    async: false
                });
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true
],*/
[
    'attribute' => 'linkQuotation.max_total_price_after_disc',
	'label'=> 'Total Selling Price (RM)',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    //'footer' => true
],
[
    'attribute' => 'linkQuotation.max_total_discount_value',
	'label'=> 'Total Discount Value (RM)',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    //'footer' => true
],
/*[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Total Discount Value (RM)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = 1;
        $price = $widget->col(3, $p);
        $amt = $qty * $price;
		$discount1 = $widget->col(2, $p)/100;

        return  $amt * $discount1;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'autoFooter'=>false,
    'footer'=>false
],*/
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Accumulate Discount Rate %', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $price = $widget->col(2, $p);
        $total_discount_value = $widget->col(3, $p);

        return  $price != 0 ? ($total_discount_value/$price) * 100 : 0;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'footer'=>true,
    'pageSummary' => true,
]
];

Pjax::begin( [
    'id' => 'pjax-item-discount-grid',
    'timeout' => 5000, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'POST']]
);

$accumulate_discount_rate = 0;
$total_price = 0;
$total_discount = 0;
$total_discount_value = 0;
$total_discount_value2 = 0;
$grand_total_discount = 0;
$max_total_price_after_disc = 0;

foreach($dataProvider->models as $m)
{
    $price = $m->linkQuotation->total_price;
    $discount = $m->linkQuotation->total_discount;
    $amt = $price;
    $total_discount_value += $m->linkQuotation->max_total_discount_value;
    $total_discount_value2 += $m->linkQuotation->max_total_discount_value2;
    $total_price += $amt;
    $max_price_after_disc = $m->linkQuotation->max_total_price_after_disc;
    $max_total_price_after_disc += $max_price_after_disc;
}

$accumulate_discount_rate = number_format(($total_discount_value/$total_price)*100,2);

$grand_total_discount = $modelQuotation->total_discount;
$grand_total_discount2 = $modelQuotation->total_discount2;

$total_discount_price = $grand_total_discount/100 * $total_price;
$total_discount_price2 = $grand_total_discount2/100 * $total_discount_price;

$balance_buffer_discount_value = $total_discount_value - $total_discount_price;
$balance_buffer_discount_value2 = $balance_buffer_discount_value - $total_discount_value2 - $total_discount_price2;

$grand_total = $total_price - $total_discount_price - $total_discount_price2;

$balance_accumulate_discount_rate = number_format(($balance_buffer_discount_value/$grand_total)*100,2);
$balance_accumulate_discount_rate2 = number_format(($balance_buffer_discount_value2/$grand_total)*100,2);

ActiveForm::begin();

echo GridView::widget([
    'id' => 'product-discount-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
	'options' => [ 'style' => 'table-layout:fixed;' ],
    'showFooter' => true,
    'afterFooter'=>'
        <tr class="table-warning kv-page-summary"><td colspan="2" align="right">Total</td><td align="right">'.number_format($max_total_price_after_disc,2).'</td><td align="right">'.number_format($total_discount_value,2).'</td><td align="right">'.$accumulate_discount_rate.'</td></tr>
        <tr class="table-warning kv-page-summary"><td colspan="3" align="right">Balance Buffer</td><td align="right">'.number_format($balance_buffer_discount_value,2).'</td><td align="right">'.$balance_accumulate_discount_rate.'</td></tr>
        <tr class="table-warning kv-page-summary"><td colspan="3" align="right">VIP Balance Buffer</td><td align="right">'.number_format($balance_buffer_discount_value2,2).'</td><td align="right">'.$balance_accumulate_discount_rate2.'</td></tr>
        <!--<tr class="table-warning kv-page-summary"><td colspan="3" align="right">Accumulate Discount Rate (%)</td><td align="right">'.number_format($accumulate_discount_rate,2).'</td></tr>-->
        <tr class="table-warning kv-page-summary"><td colspan="4" align="right">Discount (%)</td><td align="right"><input type="text" name="Quotation[total_discount2]" style="text-align: right" value="'.$grand_total_discount2.'"/></td></tr>
    ',
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
        'after'=>Html::a('<i class="fas fa-save"></i> Apply Discount', ['/quotation-group/apply-discount-vip', 'id' => Yii::$app->request->get('id')], [
            'id' => 'apply-discount-btn', 
            'class' => 'btn btn-info btn-sm float-right',
            'data-pjax' => "#pjax-quotation-grid", 
        ])
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products'
]);

ActiveForm::end();

Pjax::end();

Alert::widget();

$total = $dataProvider->getTotalCount();
$id = Yii::$app->request->get('id');

$url = Yii::$app->urlManager->createUrl(['quotation-group/select-discount-vip', 'id'=>Yii::$app->request->get('id')]);
$js = <<<JS

    $("#apply-discount-btn").on('click', function(e) {
        e.preventDefault(); 
        $('#product-discount-grid-container').css('opacity', '0.5');
        $('#product-discount-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
        $('#quotation-grid-container').css('opacity', '0.5');
        $('#quotation-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')

        let promise = new Promise((resolve)=> {
            var data=$("form").serialize();
            $.ajax({
                type: 'POST',
                url: this.href,
                traditional: true,
                data: data,
                success: function(data) {
                    $("#quotation-grid").html(data);
                    resolve();
                }
            });
        });

        promise.then((res)=> {
            $.ajax({
                type: 'POST',
                url: '$url',
                traditional: true,
                success: function(data) {
                    $("#product-discount-grid").html(data);
                    //$.pjax.reload({container: '#pjax-item-discount-grid'});
                }
            });
            
        });

        return false;
    });
JS;

$this->registerJs($js, $this::POS_END);

?>