
<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use backend\components\Alert;

use common\models\search\QuotationItemSearch;
?>

<?php

   $bordered =1;
   $striped=0;
   $condensed=1;
   $responsive=1;
   $hover=0;
   $pageSummary=1;
   $heading="<h4>$this->title</h4>";
   $exportConfig=0;

    $searchModel = new QuotationItemSearch();
	$params = array_merge(['quotation_id'=>$model->quotation_id], Yii::$app->request->queryParams);
	$dataProvider = $searchModel->search($params);
	$dataProvider->query->andWhere(['product_parent_id'=>$model->id]);

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
	'hidden' => true,
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
    'label' => 'PICTURE',
    'hidden'=> true,
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
    'hidden'=> true,
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
    'hidden'=> true,
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
                $.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000});
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
    'hidden'=> true,
    'label' => 'QUANTITY', 
    'attribute' => 'quantity', 
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
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
    'hidden'=> true,
    //'pageSummary' => true,
    //'footer' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Price', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $qty = $widget->col(6, $p);
        $price = $widget->col(9, $p);
        $amt = $qty * $price;
		$discount1 = $widget->col(7, $p)/100;
		$discountrm = $widget->col(8, $p);

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
    'hidden'=> true,
    'template' => '{delete}',
    'buttons' => [
        
        'delete' => function ($url, $model) {
            return Html::a('<span class="fas fa-trash-alt"></span>', ['#'], [
                'class' => 'pjax-delete-link',
                'delete-url' => $url,
                'pjax-container' => 'pjax-product-grid'
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
    $discount = $m->discount;
    $discountrm = $m->discountrm;
    $amt = $qty*$price;
    $total_discount_value += ($amt*$discount) + $discountrm;
    $total_price += $amt;
    $total_amt += ($amt - (($amt * $discount/100)+$discountrm));
}

$accumulate_discount_rate = $total_price > 0 ? number_format(($total_discount_value/$total_price),2) : 0;
$total_disc = $total_price * $accumulate_discount_rate/100;
$total_disc = round($total_disc / 0.05) * 0.05;
 

/*echo GridView::widget([
    'id' => 'product-grid',
	'showHeader' => false,
	'panel' => [
        'after' => '',
        'heading' => 'Component:',
        'type' => 'primary',
        'before' => '',
    ],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'footerRowOptions' => ['class' => 'table-warning kv-page-summary', 'style'=>'display:none;'],
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    // set your toolbar
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // parameters from the demo form
    'bordered' => $bordered,
    'striped' => $striped,
    'condensed' => $condensed,
    'responsive' => $responsive,
    'hover' => $hover,
    'showPageSummary' => false,
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'product',
    'itemLabelPlural' => 'products'
]);

*/

Alert::widget();

$total = $dataProvider->getTotalCount();

?>

<div class="card">
  <div class="card-header">
    Component:
  </div>
  <div class="card-body">
	<table class="table">
		<?php $i=0;?>
		<?php foreach($dataProvider->models as $m):?>
        <?php $childProduct = \common\models\Product::findOne($m->product_id)?>
			<tr>
				<td><?= ++$i;?></td>
				<td><?= $childProduct->code?></td>
				<td><?= $childProduct->name?></td>
			</tr>
		<?php endforeach;?>
	</table>
  </div>
</div>
