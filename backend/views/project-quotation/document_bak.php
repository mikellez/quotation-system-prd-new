
<div style="text-align: right">Date: <?= date("d/m/Y", time())?></div>
<table width="100%" style="font-family: serif;" cellpadding="10">
	<tr>
		<td width="45%" style="">
			<span style="font-size: 7pt; color: #555555; font-family: sans;">FROM:</span>
			<br />
			<br />Admin Sdn Bhd
			<br />111, Jalan Shah Alam, Taman Shah Alam
			<br />Phone: 03-11111111
			<br />Email: test@email.com
		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" style="">
			<span style="font-size: 7pt; color: #555555; font-family: sans;">TO:</span>
			<br />
			<br /><?=$model->company?>
			<br /><?=$model->address?>
			<br />Phone: <?=$model->telephone?>
			<br />Email: <?=$model->email?>
		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" style="">
			<span style="font-size: 14pt; color: #555555; font-family: sans; font-weight: bold">Quotation: #<?= $model->doc_no?></span>
			<br /><br />
			<br />Order ID: <?= $model->id?>
			<br />Account: 968-34567
			<br />
		</td>
	</tr></table>
<br />
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<thead>
<tr rowspan="2">
<td width="10%">Quantity</td>
<td width="20%">Product</td>
<td width="20%">Brand</td>
<td width="30%">Description</td>
<td width="10%">Price</td>
<td width="5%">Disc.<br>(%)</td>
<td width="5%">Admin Disc.<br>(%)</td>
<td width="5%">Subtotal</td>
</tr>
</thead>
<tbody>
<!-- ITEMS HERE -->
<?php $total = 0;?>
<?php foreach($model->item as $item):?>
<?php 
	$qty = $item->quantity;
	$price = $item->standard_costing;
	$amt = $qty * $price;
	$discount = $item->discount/100;
	$discount2 = $item->discount2/100;
?>
<?php $subtotal = $amt - ($amt * $discount) - ($amt * $discount2); ?>
<?php $total += $subtotal;?>
<tr>
	<td align="center"><?= $item->quantity?></td>
	<td><?= $item->name?></td>
	<td><?= $item->brand_name?></td>
	<td><?= $item->description?></td>
	<td class="cost"><?= number_format($item->retail_base_price,2)?></td>
	<td class="cost"><?= number_format($item->discount,2)?></td>
	<td class="cost"><?= number_format($item->discount2,2)?></td>
	<td class="cost"><?= number_format($subtotal,2)?></td>
</tr>
<?php endforeach;?>
<!-- END ITEMS HERE -->
<tr>
<td class="blanktotal" colspan="6" rowspan="6"></td>
<td class="totals"><b>TOTAL:</b></td>
<td class="totals cost"><b>RM <?= $total?></b></td>
</tr>
</tbody>
</table>
<!--<div style="text-align: center; font-style: italic;">Payment terms: payment due in 30 days</div>-->