
<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use backend\components\Alert;

use common\models\search\QuotationItemSearch;
use common\models\Quotation;
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
    if(!is_object($model->linkQuotation)) {
        return false;
    }
	$params = array_merge(['quotation_id'=>$model->id, 'active'=>1], Yii::$app->request->queryParams);
	$dataProvider = $searchModel->search($params);
    $modelQuotation = Quotation::findOne($model->linkQuotation->id);

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
    Product:
  </div>
  <div class="card-body">
	<table class="table">
		<?php $i=0;?>
        <thead>
			<tr rowspan="2">
			<td width="6%">No</td>
			<td width="10%">Doc No</td>
		</tr>
	    </thead>
        <?php $total = 0;?>
		<?php $count = 0;?>
		<?php $total_disc = 0;?>
		<?php $total_before_disc = 0;?>
		<?php foreach($dataProvider->models as $item):?>
			<tr>
				<td><?= ++$i;?></td>
				<td><?= $item->linkQuotation->doc_no?></td>
            </tr>
		<?php endforeach;?>
	</table>
  </div>
</div>
