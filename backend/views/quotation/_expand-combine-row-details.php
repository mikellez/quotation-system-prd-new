
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
	$params = array_merge(['quotation_id'=>$model->linkQuotation->id, 'link_quotation_id'=>null], Yii::$app->request->queryParams);
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
			<td width="10%">Brand</td>
			<td width="10%">Image</td>
			<td width="15%">Model</td>
			<td width="30%">Description</td>
			<td width="6%">Qty</td>
			<td width="15%">Unit Price (RM)</td>
			<td width="15%">Total (RM)</td>
		</tr>
	    </thead>
        <?php $total = 0;?>
		<?php $count = 0;?>
		<?php $total_disc = 0;?>
		<?php $total_before_disc = 0;?>
		<?php foreach($dataProvider->models as $item):?>
		<?php if($item->product_parent_id > 0) continue; ?>
		<?php 
			$qty = $item->quantity;
			$price = $item->retail_base_price;
			$amt = $qty * $price;
			$discount = $item->discount/100;
			$discount2 = $item->discount2/100;
			$discountrm = $item->discountrm;
			$discount3 = $discountrm > 0 ? $discountrm*$qty : 0;
		?>
		<?php $total_disc += (($amt * $discount) - ($amt * $discount2)) + $discount3; ?>
		<?php $subtotal = $amt - ($amt * $discount) - ($amt * $discount2) - $discount3; ?>
		<?php $unitprice = $price - ($price * $discount) - ($price * $discount2) - ($discount3/$qty);?>
		<?php $total += $subtotal;?>
		<?php $total_before_disc += $amt;?>
			<tr>
				<td><?= ++$i;?></td>
				<td><?= $item->brand_name ? $item->brand_name : '(not set)'?></td>
                <td style="text-align: center;" >
                    <img src="<?= $item->getImageUrl()?>" width="50"/>
                </td>
                <td style="text-align: center;"><?= $item->code?></td>
                <td>
                    <b><?= $item->name?></b><br/><?= $item->description?>
                </td>
                <td align="center"><?= $item->quantity?></td>
                <td align="right"><?= number_format($item->retail_base_price_after_disc,2)?></td>
                <td align="right"><?= number_format($item->price_after_disc,2)?></td>
            </tr>
		<?php endforeach;?>
	</table>
  </div>
</div>
