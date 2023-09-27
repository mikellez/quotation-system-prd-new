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
    if($quotationModel->slave == 1) {
        $this->params['breadcrumbs'][] = ['label' => 'Project Quotation', 'url' => ['quotation-group/item', 'id' => $quotationModel->quotation_id]];
        $this->params['breadcrumbs'][] = ['label' => $quotationModel->id, 'url' => ['update', 'id' => $quotationModel->id]];
    }
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
    //;'pageSummaryOptions' => ['colspan' => 6, 'class'=>'kv-align-right kv-align-right'],
    //'header'=>'NO',
    'headerOptions'=>['class'=>'kartik-sheet-style'],
    //'footer'=> 'Accumulate Discount Rate %',
    //'footerOptions'=>['colspan'=>2]
],
[
    'label' => 'BRAND',
    'attribute' => 'brand_name',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ],
    'vAlign' => 'middle',
    'width' => '100px',
],
[
    'class' => 'kartik\grid\ExpandRowColumn',
    'width' => '50px',
    'value' => function ($model, $key, $index, $column) {
        return $model->product_type == 'service_package' ? GridView::ROW_COLLAPSED : null;
    },
    // uncomment below and comment detail if you need to render via ajax
    // 'detailUrl' => Url::to(['/site/book-details']),
    'detail' => function ($model, $key, $index, $column) {
        return Yii::$app->controller->renderPartial('/quotation/_expand-row-details', ['model' => $model]);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'expandOneOnly' => true
],
[
    'label' => 'PICTURE',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions' => [
        'style'=>'width:100px'
    ],
    'content' => function($model) {
        /**  @var \common\models\Product $model */
        return Html::img($model->getImageUrl(), ['style'=> 'width: 100%']);
    },

],
[
    'label'=>'NAME',
    'attribute' => 'name',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'label'=>'PRODUCT CODE',
    'attribute' => 'code',
    'vAlign' => 'middle',
    'width' => '100px',
    'headerOptions'=> [
        'class'=>'text-center'
    ],
    'contentOptions'=> [
        'class'=>'text-center'
    ]
],
[
    'class' => 'kartik\grid\EditableColumn',
    'label' => 'Description', 
    'attribute' => 'description', 
    'editableOptions' => [
        'header' => 'Description', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
        'options' => [
           //'editableValueOptions'=>['type'=>'text']
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({
			'container': '#pjax-product-grid', 
			'timeout': 5000,
			'type': 'GET'
		});
            }",
        ]
    ],
    'hAlign' => 'left', 
    'vAlign' => 'middle',
    'width' => '30%',
    'contentOptions' => [
        //'style' => 'white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'
    ],
    //'format' => ['text'],
    //'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'label' => 'QUANTITY', 
    'attribute' => 'quantity', 
    'editableOptions' => [
        'header' => 'Quantity', 
        //'inputType' => \kartik\editable\Editable::INPUT_SPIN,
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
           'pluginOptions' => ['min' => 0, 'max' => 999999],
           'editableValueOptions'=>['type'=>'number']
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000});
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '7%',
    'format' => ['integer'],
    //'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discount', 
    'label' => 'Discount(%)',
    'hidden'=> true,
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
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000});
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discountrm', 
    'label' => 'Discount(RM)',
    'hidden'=> true,
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
    'editableOptions' => [
        'header' => 'Discount(RM)', 
        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
        'options' => [
            'pluginOptions' => ['min' => 0, 'max' => 5000]
        ],
        'asPopover' => false,
        'pluginEvents'=>[
            "editableSuccess"=>"function(event, val, form, data) {
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000});
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true
],
[
    'label'=>'SLP (RM)',
    'attribute' => 'retail_base_price',
    'vAlign' => 'middle',
    'hAlign' => 'right',
    'width' => '100px',
    'format' => ['decimal', 2],
    //'pageSummary' => true,
    //'footer' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Price', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(7, $p);
        $price = $widget->col(10, $p);
        $amt = $qty * $price;
		$discount1 = $widget->col(8, $p)/100;
		$discountrm = $widget->col(9, $p);

        return  $amt - (($amt * $discount1) + $discountrm);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '7%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    //'pageSummary' => true,
    'footer' => true,
    'hidden'=> true
],
[
    'class' => 'kartik\grid\ActionColumn',
    'template' => '{delete}',
    'buttons' => [
        
        'delete' => function ($url, $model) {
            return Html::a('<span class="fas fa-trash-alt"></span>', ['#'], [
                'class' => 'pjax-delete-link',
                'delete-url' => $url,
                'pjax-container' => 'pjax-product-grid',
		'clientOptions' => 'GET'
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
    'deleteOptions' => ['title' => 'Delete product', 'data-toggle' => 'tooltip'],*/
    'headerOptions' => ['class' => 'kartik-sheet-style'],
]
];

$accumulate_discount_rate = 0;
$total_price = 0;
$total_discount = 0;
$total_discount_value = 0;
$total_amt = 0;

foreach($dataProvider->models as $m)
{
    $qty = $m->quantity;
    $price = $m->retail_base_price;
    $discount = $m->discount/100;
    $discountrm = $m->discountrm;
    $amt = $qty*$price;
    $discount2 = $discountrm > 0 ? $discountrm*$qty : 0;
    $total_discount_value += ($amt*$discount) + $discount2;
    $total_price += $amt;
    $total_amt += ($amt - (($amt * $discount)+$discount2));
}

$accumulate_discount_rate = $total_price > 0 ? number_format(($total_discount_value/$total_price*100),2) : 0;
$total_disc = $total_price * $accumulate_discount_rate/100;
$total_disc = number_format($total_disc, 0);
//$total_disc = $total_price * $accumulate_discount_rate/100;
//$total_disc = round($total_disc / 0.05) * 0.05;
 

echo GridView::widget([
    'id' => 'product-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'showFooter' => true,
    'afterFooter'=>'
	<tr class="table-warning kv-page-summary">
		<td colspan="8" align="right">Grand Total (RM)</td>
		<td align="right">'.number_format($quotationModel->total_price,2).'</td>
		<td>&nbsp;</td>
	</tr>',
    'beforeFooter'=>'
	<tr class="table-warning kv-page-summary">
		<td colspan="8" align="right">Total (RM)</td>
		<td align="right">'.number_format($quotationModel->total_price_after_disc,2).'</td>
		<td>&nbsp;</td>
	</tr>
	<tr class="table-warning kv-page-summary">
		<td colspan="8" align="right">Discount ('.number_format($quotationModel->accumulate_discount_rate,2).'%)</td>
		<td align="right">-'.($quotationModel->total_discount_value+$quotationModel->total_discountrm_value+$quotationModel->total_admin_discount_value).'</td>
		<td>&nbsp;</td>
	</tr>',
    'footerRowOptions' => ['class' => 'table-warning kv-page-summary', 'style'=>'display:none;'],
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
        'content' =>
            Html::button('<i class="fas fa-plus"></i> Add Product', [
                'value' => Yii::$app->urlManager->createUrl(['/product/select', 'id' => Yii::$app->request->get('id')]),
                'class' => 'btn btn-success btn-sm showModalButton',
                'id' => 'BtnModalId',
                'data-toggle'=> 'modal',
                'data-target'=> '#modal'
            ]). '&nbsp;'.
            Html::a('<i class="fas fa-edit"></i> Edit Header', [ '/quotation/update', 'id' => Yii::$app->request->get('id')],[
                'class' => 'btn btn-warning btn-sm showModalButton',
                'id' => 'BtnModalAddId',
            ]).'&nbsp;'.
            ($quotationModel->slave != 1 ? Html::button('<i class="fas fa-edit"></i> Edit Discount', [
                'value' => Yii::$app->urlManager->createUrl(['/quotation/select-discount', 'id' => Yii::$app->request->get('id')]),
                'class' => 'btn btn-warning btn-sm showModalButton',
                'id' => 'BtnModalId',
                'data-toggle'=> 'modal',
                'data-target'=> '#modal'
            ]) : ''),
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
        'after'=>Html::button('<i class="fas fa-save"></i> Generate Quotation', ['id' => 'generate-quotation-btn', 'class' => 'btn btn-info btn-sm float-right']).Html::button('<i class="fas fa-file"></i> Preview Quotation', ['id' => 'preview-quotation-btn', 'class' => 'btn btn-default btn-sm float-right mr-1']),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products'
]);

Alert::widget();

$total = $dataProvider->getTotalCount();
$js = <<<JS

    $('.pjax-delete-link').one('click', function(e) {
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
                $('#product-grid-container').css('opacity', '0.5');
                $('#product-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#' + pjaxContainer, 'timeout': 5000});
            });
        }
    });

JS;

$this->registerJs($js, $this::POS_END);

?>
