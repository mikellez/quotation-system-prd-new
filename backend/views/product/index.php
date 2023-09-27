<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
['class' => 'yii\grid\SerialColumn'],
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

        <!-- /.card-header -->
        <div class="card-body p-0">
            <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm'],
            'columns' => $gridColumns
        ]); ?> 
        </div>
        <!-- /.card-body -->
    </div>
</div>
