<?php 
$terms_n_conditions[] = "All cheques are made payable to “Redawn Marketing Sdn Bhd and crossed with “A/C Payee only”";
$terms_n_conditions[] = "Bank details: Alliance Bank (M) Sdn Bhd ; Account no.: 12015 001 007 698 1";
$terms_n_conditions[] = "Terms of payment, ~ Cash upon order confirmation.";
$terms_n_conditions[] = "Validity of quotation : 14days";
$terms_n_conditions[] = "We reserve the rights to adjust our prices accordingly subject to Malaysian customs and taxations imposed.";
$terms_n_conditions[] = "Prices quoted shall be subjected to any changes or addition in any form of taxes as announced by the Government from time to time (including but not limited to Sales and Service tax) during the validity period of this quotation.";
$terms_n_conditions[] = "Once confirmation of order is received, no cancellation will be entertained and strictly no refunds allowed.";
$terms_n_conditions[] = "Products offered are based on items listed in the quotation and if not specifically stated will be deemed not provided.";
$terms_n_conditions[] = "*Delivery will be made in full lorry basic within Klang Valley. Transportation charges will be imposed if delivery is in small quantities.";
$terms_n_conditions[] = "*For Outstation delivery, transportation charges will apply.";
$terms_n_conditions[] = "*If goods are not taken after one month of its arrival, storage charges will be imposed.";
$terms_n_conditions[] = "Approx. 12 - 14 weeks subject to stock availability upon confirmation of delivery schedule given";
$terms_n_conditions[] = "In the event of any changes in quantity or specifications of purchase order, we require at least 10 weeks' notification before date of delivery in order to necessitate any changes.";
$terms_n_conditions[] = "The special project price is valid for this project only and will not be a reference for any other projects.";
$terms_n_conditions[] = "Lump-sum discount shall not be used as the discount ratio on individual model.";
$terms_n_conditions[] = "Quotation price is on nett basis and shall not entitled to any other campaign, discounts, incentive and etc.";
$terms_n_conditions[] = "Specifications are subject to change without prior notice for product improvement.";
?>

<!--<div style="text-align: right">Date: <?= date("d/m/Y", time())?></div>-->
<div>
	<img src="<?=$model->company0->getImageUrl();?>" style="width: 100%;"/>
</div>
<table width="100%" style="font-family: serif; font-size: 8pt;" cellpadding="10">
	<tr>
		<td width="70%" style="">
			<table width="100%" style=" color: #555555; font-family: sans;">
				<tbody>
					<tr>
						<td width="8%">To</td>
						<td>: <?= $model->client0->company?></td>
					</tr>
					<tr>
						<td>Addr</td>
						<td>: <?= $model->address?></td>
					</tr>
					<tr>
						<td>Attn</td>
						<td>: <?= $model->createdBy->name?></td>
					</tr>
					<tr>
						<td>e</td>
						<td>:</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td width="30%" style="">
			<table width="100%">
				<tbody>
					<tr>
						<td colspan="2" align="center" style="font-size: 10pt; color: #555555; font-family: sans; background-color: rgb(129, 209, 193); border: 1px; height: 34px;">
							Quotation
						</td>
					</tr>
					<tr>
						<td >Quotation</td>
						<td>: <?= $model->doc_no?></td>
					</tr>
					<tr>
						<td>Date</td>
						<td>: <?= date("d/m/Y", time())?></td>
					</tr>
					<tr>
						<td>Terms</td>
						<td>: CASH</td>
					</tr>
					<tr>
						<td>Validity</td>
						<td>: 14 days</td>
					</tr>
					<tr>
						<td>P.I.C</td>
						<td>: <?= $model->createdBy->name?></td>
					</tr>
					<tr>
						<td>A/C Code</td>
						<td>: <?= $model->createdBy->code?></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
<br />
<span style="text-decoration: underline; font-weight: bold; font-size: 14pt;">RE: <?= $model->doc_title?></span>
<table class="items" width="100%" style="font-size: 10pt; border-collapse: collapse; " cellpadding="8">
	<thead>
		<tr>
			<td colspan="5" style="background-color: rgb(129, 209, 193); border: 1px; padding: 0.5%; text-align-last: center; color:white; font-size: 14pt;"><?= $model->doc_name?></td>
			</tr>
			<tr rowspan="2">
			<td width="5%">No.</td>
			<td width="10%">Brand</td>
			<td width="10%">Image</td>
			<td width="15%">Model</td>
			<td width="30%">Description</td>
			<td width="5%">Quantity</td>
			<td width="15%">Unit Price (RM)</td>
			<td width="15%">Total (RM)</td>
		</tr>
	</thead>
	<tbody>
		<!-- ITEMS HERE -->
		<?php $total = 0;?>
		<?php $count = 0;?>
		<?php $total_disc = 0;?>
		<?php $total_before_disc = 0;?>
		<?php foreach($model->item as $item):?>
		<?php 
			$qty = $item->quantity;
			$price = $item->retail_base_price;
			$amt = $qty * $price;
			$discount = $item->discount/100;
			$discount2 = $item->discount2/100;
			$discountrm = $item->discountrm;
		?>
		<?php $total_disc += (($amt * $discount) - ($amt * $discount2)) + $discountrm; ?>
		<?php $subtotal = $amt - ($amt * $discount) - ($amt * $discount2) - $discountrm; ?>
		<?php $unitprice = $price - ($price * $discount) - ($amt * $discount2) - $discountrm;?>
		<?php $total += $subtotal;?>
		<?php $total_before_disc += $amt;?>
		<tr>
			<td style="text-align: center;"><?= ++$count?></td>
			<td style="text-align: center;"><?= $item->brand_name?></td>
			<td style="text-align: center;"><img src="<?= $item->getImageUrl()?>" width="50" height="50"/></td>
			<td style="text-align: center;"><?= $item->code?></td>
			<td><b><?= $item->name?></b><br/><?= $item->description?></td>
			<td align="center"><?= $item->quantity?></td>
			<td align="right"><?= number_format($unitprice,2)?></td>
			<td align="right"><?= number_format($subtotal,2)?></td>
		</tr>
		<?php endforeach;?>
		<!-- END ITEMS HERE -->
		<!--<tr>
			<td class="blanktotal" colspan="5" style="border-top: 1px dotted;"></td>
			<td class="totals" align="left"><b>TOTAL (RM):</b></td>
			<td class="totals total" align="right" style="background-color: rgb(216, 252, 219) !important;"><b>RM <?= number_format($total_before_disc, 2)?></b></td>
		</tr>
		<tr>
			<td class="blanktotal" colspan="5" ></td>
			<td class="totals" align="left"><b>Discount (RM)</b></td>
			<td class="totals total" align="right" style="background-color: rgb(216, 252, 219) !important;"><b>-RM <?= number_format($total_disc, 2)?></b></td>
		</tr>-->
		<tr>
			<td class="blanktotal" colspan="6" ></td>
			<td class="totals" align="left"><b>TOTAL (RM):</b></td>
			<td class="totals total" align="right" style="background-color: rgb(216, 252, 219) !important;"><b><?= number_format($total, 2)?></b></td>
		</tr>
	</tbody>
</table>
<br/>
<table width="100%" style="font-size: 10pt">
	<tbody>
		<tr>
			<td  colspan="2" width="80%" style="background-color: rgb(129, 209, 193); border: 1px; font-weight:bold; height: 25px;">
				Payment terms and conditions:-
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>
				<?= $model->payment_tnc?>
			</td>
		</tr>
		<?php $count=0;?>
		<!--<?php foreach($terms_n_conditions as $term_n_condition):?>
		<tr>
				<td width="3%">
					<p><?= ++$count?></p>
				</td>
				<td width="77%" colspan="2">
					<p><?= $term_n_condition?></p>
				</td>
		</tr>
		<?php endforeach;?>-->
	</tbody>
</table>

<htmlpagefooter name="LastPageFooter" style="font-size: 10pt;">
<br/>
<p>Trust the above quotation meets your requirement. Should you require further info and clarifications, please do not hesitate to contact us.</p>
<br/>
<p>Thank you.</p>
<br/>
<p>Yours truly,</p>
<p style="font-weight: bold;"><?= $model->company0->company//\common\models\Company::findOne($model->createdBy->company)->company?></p>
<p><?= $model->createdBy->name?></p>
<p><?= $model->createdBy->phoneno?></p>
<p>
<?php if($authassignmentModel->item_name=="officer"):?>
	Retail Sales Executive
<?php else:?>
	<?= ucfirst($authassignmentModel->item_name)?>	
<?php endif;?>
</p>
</htmlpagefooter>

<sethtmlpagefooter name="LastPageFooter" value="1" />
