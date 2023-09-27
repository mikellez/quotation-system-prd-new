<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use backend\components\Alert;

?>

<?php

    $this->title = 'Select Quotation';
    $this->params['breadcrumbs'][] = $this->title;

   $bordered =1;
   $striped=1;
   $condensed=1;
   $responsive=1;
   $hover=1;
   $pageSummary=0;
   $heading='';
   $exportConfig=0;

$gridColumns = [
[
    'class'=>'kartik\grid\SerialColumn',
    'contentOptions'=>['class'=>'kartik-sheet-style'],
    'width'=>'36px',
    'pageSummary'=>'Total',
    'pageSummaryOptions' => ['colspan' => 4],
    'header'=>'',
    'headerOptions'=>['class'=>'kartik-sheet-style']
],
[
    'attribute' => 'doc_no',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'attribute' => 'doc_name',
    'vAlign' => 'middle',
    'width' => '210px',
],
[
    'header'=>'Total Proposal Amount',
    'attribute' => 'total_price',
    'vAlign' => 'middle',
    'width' => '210px',
    'format' => ['decimal', 2],
    'pageSummary' => true,
    'footer' => true
],
[
    'class' => 'kartik\grid\CheckboxColumn',
    'headerOptions' => ['class' => 'kartik-sheet-style'],
    'pageSummary' => '<small>(amounts in $)</small>',
    'pageSummaryOptions' => ['colspan' => 1, 'data-colspan-dir' => 'rtl']
],
];


Pjax::begin( [
    'id' => 'pjax-quotation-search-grid',
    'timeout' => false, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'POST']]
);

echo GridView::widget([
    'id' => 'quotation-search-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => false, // pjax is set to always true for this demo
    'toolbar' =>  [
        [
            'content' =>
                Html::a('<i class="fas fa-redo"></i>', ['select', 'id'=>Yii::$app->request->get('id')], [
                    'id' => 'reset-quotation-search-grid',
                    'class' => 'btn btn-outline-secondary',
                    'title'=>'Reset Grid',
                    'data-pjax' => "#pjax-quotation-search-grid", 
                ]), 
            'options' => ['class' => 'btn-group mr-2']
        ]
    ],
    // set your toolbar
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
        'after'=>Html::a('<i class="fas fa-plus"></i> Add quotation', ['/quotation-group/quotation-add', 'id' => Yii::$app->request->get('id')], [
            'id' => 'add-quotation-search-grid', 
            'class' => 'btn btn-info btn-sm float-right',
            'data-pjax' => "#pjax-quotation-grid", 
        ]),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
    'itemLabelSingle' => 'quotation',
    'itemLabelPlural' => 'quotations'
]);

Pjax::end();

Alert::begin();
?>

<?php
$url = Yii::$app->urlManager->createUrl(['item', 'id'=>Yii::$app->request->get('id')]);

$js = <<<JS
    $("#add-quotation-search-grid").on('click', function(e){
        e.preventDefault();

        var keys = $('#quotation-search-grid').yiiGridView('getSelectedRows');
        if(keys=="") {
            swal("Oops..","Please select more than one quotation!","error");
            return false;
        }
        /*$.pjax({
            type: 'POST',
            url: this.href,
            data: { 'quotationSearch[id]' : keys.join() },
            container: '#pjax-quotation-search-grid',
        });*/

        $.ajax({
            type: 'POST',
            url: this.href,
            data: { 'QuotationSearch[id]' : keys.join() },
            traditional: true,
            success: function(data) {
                //$("#quotation-grid").html(data);
                $.pjax.reload({container: '#pjax-quotation-grid', 'timeout': 5000});
                swal("Success..","Your quotation is added successfull!","success");
                $("#reset-quotation-search-grid").trigger("click");
            }
        });

        return false;
    });

    $("#reset-quotation-search-grid").on('click', function(e) {
        e.preventDefault(); 

        var id='#quotation-search-grid';
        var inputSelector=id+'-filters input, '+id+'-filters select';
        $(inputSelector).each( function(i,o) {
                $(o).val('');
        });
        var data=$.param($(inputSelector));
        $.ajax({
            type: 'POST',
            url: this.href,
            traditional: true,
            success: function(data) {
                $("#quotation-search-grid").html(data);
            }
        });
        /*$.pjax({
            type: 'POST',
            url: this.href,
            container: '#pjax-quotation-search-grid',
        });*/

        return false;
    });
JS;

$this->registerJs($js, $this::POS_END);
?>