<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profit Analysis';
$this->params['breadcrumbs'][] = $this->title;

$bordered =1;
$striped=0;
$condensed=1;
$responsive=1;
$hover=0;
$pageSummary=1;
$heading="<h4>$this->title</h4>";
$exportConfig=0;

$addon = <<< HTML
<div class="input-group-append">
    <span class="input-group-text">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
HTML;

$gridColumns = [
[
    'class'=>'kartik\grid\SerialColumn',
    'contentOptions'=>['class'=>'kartik-sheet-style'],
    //'width'=>'5%',
    'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style'],
],

[
    'label'=>'Sales Person',
    'attribute'=> 'createdBy.username',
    'mergeHeader'=>true
],

//'created_at:datetime',
[
    'attribute'=>'created_at',
    'label'=>'Created At',
    'format'=>'text',
    'filter'=> '<div class="input-group drp-container">'.
        DateRangePicker::widget([
        'name'=>'KV[created_at]',
        'value'=>'',
        'useWithAddon'=>true,
        'pluginOptions'=>[
            'singleDatePicker'=>true,
            'showDropdowns'=>true
        ]
    ]). $addon. "</div>",
    'width'=>'7%',
    'content'=>function($data){
        return Yii::$app->formatter->asDatetime($data['created_at'], "php:d/m/Y");
    }
],

'doc_no',
[
    'attribute'=>'project_name',
    'enableSorting'=>true,
    //'contentOptions'=>['class'=>'kartik-sheet-style'],
    //'width'=>'5%',
    'pageSummary'=>'Total',
//    'pageSummaryOptions' => ['colspan' => 2],
    'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style'],
],
[
    'attribute' => 'total_price_after_disc',
	'label'=> 'Total Sales Amount',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    //'footer' => true
],
[
    'attribute' => 'total_cost',
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    //'footer' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'attribute' => 'total_agent_comm',
    'label' => 'Total Agent Commission (RM)', 
    'hAlign' => 'right',
    'width' => '10%',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    'footer' => true
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'label' => 'Total Agent Commission (%)', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $total_agent_comm = $widget->col(7, $p);
        $total_price_after_disc = $widget->col(5, $p);
        $total_agent_comm_percentage =  $total_agent_comm / $total_price_after_disc;

        return  $total_agent_comm_percentage;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => \common\models\Quotation::getTotalAgentCommissionPercentage($dataProvider->models),
    /*'pageSummaryFunc'=>function ($data) { 
        print_r($data);die;
        return $data;
    },*/
    'autoFooter'=>false,
    'footer'=>false
],
[
    'class' => 'kartik\grid\FormulaColumn', 
    'label' => 'Total Gross Margin', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $total_agent_comm = $widget->col(7, $p);
        $total_price_after_disc = $widget->col(5, $p);
        $total_cost = $widget->col(6, $p);

        $total_gross_margin = $total_price_after_disc - $total_cost - $total_agent_comm;

        return  $total_gross_margin;
    },
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => true,
    'footer'=>false
],

[
    'class' => 'kartik\grid\FormulaColumn', 
    'label' => 'Total Gross Profit', 
    'vAlign' => 'middle',
    'value' => function ($model, $key, $index, $widget) { 
        $p = compact('model', 'key', 'index');
        $total_price_after_disc = $widget->col(5, $p);
        $total_gross_margin = $widget->col(9, $p);

        $total_gross_profit = $total_gross_margin / $total_price_after_disc;

        return  $total_gross_profit;
    },
    //'headerOptions' => ['class' => 'kartik-sheet-style'],
    'hAlign' => 'right', 
    'width' => '10%',
    'format' => ['decimal', 2],
    'mergeHeader' => true,
    'pageSummary' => \common\models\Quotation::getTotalGrossProfit($dataProvider->models),
    'footer'=>false
],

[
    'attribute' => 'status',
    'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\Quotation::getStatusList(), [
        'class' => 'form-control',
        'prompt' => 'All'
    ]),
    'format' => 'orderStatus',
    'pageSummary'=>false
],

//['class' => 'yii\grid\ActionColumn'],

];

?>
<div class="quotation-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
        </div>

        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?php 

                echo GridView::widget([
                    'id' => 'product-discount-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                    'options' => [ 'style' => 'table-layout:fixed;' ],
                    //'showFooter' => true,
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
                    'persistResize' => false,
                    'toggleDataOptions' => ['minCount' => 10],
                    'exportConfig' => $exportConfig,
                    'itemLabelSingle' => 'product',
                    'itemLabelPlural' => 'products'
                ]);

                ?>

            <?php
            /*echo  GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions'=>['class'=>'table table-striped table-bordered table-responsive'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label'=>'Sales Person',
                        'attribute'=> 'createdBy.username',
                    ],
                    'created_at:datetime',
                    //'id',
                    //'quotation_id',
                    'doc_no',
                    //'rev_no',
                    //'doc_name',
                    //'doc_title',
                    'project_name',
                    [
                        'label'=>'Total Sales Amount',
                        'attribute'=>'total_price_after_disc',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                    ],
                    [
                        'label'=>'Total Cost',
                        'attribute'=>'total_cost'
                    ],
                    [
                        'label'=>'Total Agent Commision (%)',
                        'content' => function ($model) {
                            return number_format($model->total_agent_comm / $model->total_price_after_disc,2);
                        },
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                    ],
                    [
                        'label'=>'Total Agent Commision',
                        //'attribute'=>'total_agent_comm',
                        'content' => function ($model) {
                            return $model->total_agent_comm;
                        },
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                    ],
                    [
                        'label'=>'Total Gross Margin',
                        //'attribute'=>'total_agent_comm',
                        'content' => function ($model) {
                            return number_format($model->total_price_after_disc - $model->total_cost - $model->total_agent_comm,2);
                        },
                    ],
                    [
                        'label'=>'Total Gross Profit',
                        //'attribute'=>'total_agent_comm',
                        'content' => function ($model) {
                            return number_format((($model->total_price_after_disc - $model->total_cost - $model->total_agent_comm) / $model->total_price_after_disc) * 100,2);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\Quotation::getStatusList(), [
                            'class' => 'form-control',
                            'prompt' => 'All'
                        ]),
                        'format' => 'orderStatus'
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); */
            
            ?>

        </div>

    </div>

</div>
