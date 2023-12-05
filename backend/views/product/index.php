<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
['class' => 'yii\grid\SerialColumn'],
[
    'class' => 'kartik\grid\CheckboxColumn',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'pageSummary' => '<small>(amounts in $)</small>',
    'pageSummaryOptions' => ['colspan' => 3, 'data-colspan-dir' => 'rtl']
],
[
    //'label'=>'BRAND NAME',
    'attribute'=>'brand_name',
    'headerOptions' => [
        'class'=>'text-center'
    ],
    'contentOptions' => [
        'class'=>'text-center'
    ]
],
[
    //'label'=>'BRAND NAME',
    'attribute'=>'product_type',
    'headerOptions' => [
        'class'=>'text-center'
    ],
    'contentOptions' => [
        'class'=>'text-center'
    ]
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
    'content' => function($model) {
        /**  @var \common\models\Product $model */
        return Html::img($model->getImageUrl(), ['style'=> 'width: 100%']);
    },

],
[
    //'label'=>'PRODUCT NAME',
    'attribute'=>'name',
],
[
    'label'=>'PRODUCT CODE',
    'attribute'=>'code',
    'headerOptions'=>[
        'class'=>'text-right'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ]
],
[
    'label'=>'SLP',
    'attribute'=>'retail_base_price',
    'format'=>['decimal',2],
    'headerOptions'=>[
        'class'=>'text-right'
    ],
    'contentOptions'=>[
        'class'=>'text-right'
    ]
],
//'retail_base_price',
[
    'attribute'=> 'projectCurrency.currency',
    'headerOptions'=> [
        'class'=>'text-center'
    ],
    'contentOptions'=> [
        'class'=>'text-center'
    ]
],
//'project_base_price',
//'threshold_discount',
//'project_threshold_discount',
//'admin_discount',
//'standard_costing',
//'description',
[
    'attribute' => 'status',
    'content' => function ($model) {
        /** @var \common\models\Product $model */
        return Html::tag('span', $model->status ? 'Active' : 'Draft', [
            'class' => $model->status ? 'badge badge-success' : 'badge badge-danger'
        ]);
    },
    'headerOptions'=>[
        'class'=>'text-center'
    ],
    'contentOptions'=>[
        'class'=>'text-center'
    ]
],
/*[
    'class' => 'kartik\grid\EditableColumn',
    'attribute' => 'sequence', 
    'editableOptions' => function($model, $key, $index) {
	return [
		'header' => 'Seq', 
		'inputType' => \kartik\editable\Editable::INPUT_TEXT,
		'options' => [
		   // 'pluginOptions' => ['min' => 0, 'max' => 5000]
			'id' => "sequence_{$index}"
		],
		'asPopover' => false,
		'pluginEvents'=>[
		    "editableSuccess"=>"function(event, val, form, data) {
			$('.grid-view').css('opacity', '0.5');
			$('.grid-view').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
			$.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000, 'type': 'GET'});
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
],*/
/*[
    'attribute' => 'created_at',
    'format' => ['datetime'],
    'contentOptions' => ['style' => 'white-space: nowrap']
],
[
    'attribute' => 'updated_at',
    'format' => ['datetime'],
    'contentOptions' => ['style' => 'white-space: nowrap']
],
'created_by',
'updated_by',*/
[
    'class' => 'yii\grid\ActionColumn',
    'contentOptions'=>[
        'class'=>'text-right'
    ],
    'template' => '
        <div>
            {up}{down} 
        </div>
    ',
    'buttons'=>[
        'up' => function($url,$model) { 
            return  Html::a('&nbsp;<span class="fas fa-arrow-up"></span> ', ['reorder-up', 'id'=>$model->id], [
                'class'=>'reorder-up',
                'data-id'=>$model->id,
                'title' => Yii::t('app', 'Reorder up')
            ]);
        },
        'down' => function($url,$model) { 
            return  Html::a('&nbsp;<span class="fas fa-arrow-down"></span> ', ['reorder-down', 'id'=>$model->id], [
                'class'=>'reorder-down',
                'data-id'=>$model->id,
                'title' => Yii::t('app', 'Reorder down')
            ]);
        },
    ]
],

[
    'class' => 'yii\grid\ActionColumn',
    'contentOptions'=>[
        'class'=>'text-right'
    ],
    'template' => '
        <div>
            {view} {update} {duplicate} {delete}
        </div>
    ',
    'buttons'=>[
        'duplicate' => function($url,$model) { 
            return  Html::a('&nbsp;<span class="fas fa-file"></span> ', ['duplicate', 'id'=>$model->id], [
                'class'=>'duplicate-product',
                'data-id'=>$model->id,
                'title' => Yii::t('app', 'Duplicate Product'),
                'data-confirm' => 'Are you sure you want to create duplicate for this?',
            ]);

        },
    ]
],

];


$gridColumnsExport = [
    [
        'label'=>'col_action',
        'content'=>function() {
            return 'UPDATE';
        }
    ],
    ['attribute'=>'id', 'label'=>'id'],
    ['attribute'=>'name', 'label'=>'name'],
    ['attribute'=>'brand_name', 'label'=>'brand_name'],
    ['attribute'=>'type', 'label'=>'type'],
    ['attribute'=>'product_type', 'label'=>'product_type'],
    ['attribute'=>'code', 'label'=>'code'],
    ['attribute'=>'image', 'label'=>'image'],
    ['attribute'=>'project_currency', 'label'=>'project_currency'],
    ['attribute'=>'retail_base_price', 'label'=>'retail_base_price'],
    ['attribute'=>'project_base_price', 'label'=>'project_base_price'],
    ['attribute'=>'threshold_discount', 'label'=>'threshold_discount'],
    ['attribute'=>'project_threshold_discount', 'label'=>'project_threshold_discount'],
    ['attribute'=>'admin_discount', 'label'=>'admin_discount'],
    ['attribute'=>'standard_costing', 'label'=>'standard_costing'],
    ['attribute'=>'agent_comm', 'label'=>'agent_comm'],
    ['attribute'=>'description', 'label'=>'description'],
    ['attribute'=>'status', 'label'=>'status']
];

?>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <!--<?//= Html::a('Import', ['import'], ['class' => 'btn btn-primary btn-sm']) ?>-->
            <!--<?//= Html::a('Export', ['export'], ['class' => 'btn btn-info btn-sm']) ?>-->
            <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            <?= Html::a('Import Products', ['import'], ['class' => 'btn btn-warning btn-sm']) ?>
            <?= Html::a('Delete Products', ['delete-all'], ['id'=> 'delete-products','class' => 'btn btn-danger btn-sm delete-products']) ?>
            <?php 
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumnsExport,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ])?>
            <br/><br/>
        </div>
<?php
Pjax::begin( [
    'id' => 'pjax-product-grid',
    'timeout' => 3000, 
    'enablePushState' => true, 
    'clientOptions' => ['method' => 'GET'],
    'linkSelector' => 'a[data-method="get"]'
]);
?>

        <!-- /.card-header -->
        <div class="card-body p-0">
            <?= GridView::widget([
                'id'=>'product-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm'],
            'columns' => $gridColumns
        ]); ?> 
        </div>
        <!-- /.card-body -->
    </div>
</div>

<?php
Pjax::end();
?>

<?php
$url = Yii::$app->urlManager->createUrl(['item', 'id'=>Yii::$app->request->getBodyParam('id')]);

$js = <<<JS
$(".duplicate-product").on('click', function(e){
e.preventDefault();


            $.ajax({
                type: 'POST',
                url: this.href,
                data: { 'ProductSearch[id]' : $(this).data('id') },
                traditional: true,
                success: function(data) {
                    if(data.error) {
                        alert(data.message);
                        return;
                    }
                    
                    alert("Your product is duplicated successfully!");
                    location.reload();
                }
            });

            return false;
        
});

$("#delete-products").on('click', function(e){
e.preventDefault();


            var keys = $('#product-grid').yiiGridView('getSelectedRows');
            if(keys=="") {
                alert("Please select more than one product!");
                return false;
            }
            

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
                    
                    alert("Your product is deleted successfully!");
                    location.reload();
                }
            });

            return false;
        
});

$(".reorder-up").on('click', function(e){
e.preventDefault();



            

            $.ajax({
                type: 'POST',
                url: this.href,
                data: { 'ProductSearch[id]' : $(this).data('id') },
                traditional: true,
                success: function(data) {
                    if(data.error) {
                        alert(data.message);
                        return;
                    }
                    
                    
                    location.reload();
                }
            });

            return false;
        
});

$(".reorder-down").on('click', function(e){
e.preventDefault();



            

            $.ajax({
                type: 'POST',
                url: this.href,
                data: { 'ProductSearch[id]' : $(this).data('id') },
                traditional: true,
                success: function(data) {
                    if(data.error) {
                        alert(data.message);
                        return;
                    }
                    
                    
                    location.reload();
                }
            });

            return false;
        
});
JS;

$this->registerJs($js, $this::POS_END);
?>
