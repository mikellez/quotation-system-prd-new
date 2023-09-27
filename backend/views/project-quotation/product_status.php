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
    'pageSummary'=>'Total',
    'pageSummaryOptions' => ['colspan' => 4],
    //'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
[
    'attribute' => 'name',
	'filter' => false,
	'enableSorting' => false,
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'attribute' => 'brand_name',
    'header' => 'Brand',
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ],
	'filter' => false,
	'enableSorting' => false,
    'vAlign' => 'middle',
    'width' => '100px',
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
    'vAlign' => 'middle',
    'width' => '100px',
],
[
    'attribute' => 'quantity', 
	'filter' => false,
	'enableSorting' => false,
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '7%',
    'format' => ['integer'],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discount', 
	'label' => 'Discount(%)',
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
					url: '".Yii::$app->urlManager->createUrl(['/quotation/update-status', 'id'=>Yii::$app->request->get('id')])."',
					type: 'POST'
				});
            }",
        ]
    ],
	'enableSorting' => false,
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'discount2', 
	'label' => 'Admin Disc.(%)',
	'enableSorting' => false,
    'readonly' => function($model, $key, $index, $widget) {
        return (!$model->status); // do not allow editing of inactive records
    },
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
					url: '".Yii::$app->urlManager->createUrl(['/quotation/update-status', 'id'=>Yii::$app->request->get('id')])."',
					type: 'POST'
				});
            }",
        ]
    ],
    'hAlign' => 'right', 
    'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    'pageSummary' => true
],
[
    'attribute' => 'retail_base_price',
    'headerOptions'=>[
        'class'=>'text-right'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ],
	'filter' => false,
	'enableSorting' => false,
    'vAlign' => 'middle',
    'width' => '100px',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    'footer' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'header' => 'Price After Disc.', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
		$qty = $widget->col(4, $p);
		$price = $widget->col(7, $p);
		$amt = $qty * $price;
		$discount1 = $widget->col(5, $p)/100;
		$discount2 = $widget->col(6, $p)/100;
        return  $amt - ($amt * $discount1) - ($amt * $discount2);
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '7%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'footer' => true
],
[
    'header'=>'Threshold',
    'content' => function($model) {
        /**  @var \common\models\Product $model */
        return $model->discount > $model->threshold_discount ? "<span class='fas fa-check-circle text-danger'></span>" : "";
    },
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ]
]
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
    'showPageSummary' => $pageSummary,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => false,
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