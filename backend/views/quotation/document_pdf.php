<!--<span style="text-decoration: underline; font-weight: bold; font-size: 14pt;">RE: <?= $model->doc_title?></span>
<br/>-->

		<!--<span align="left" style="background-color: rgb(129, 209, 193); border: 1px; padding: 0.5%; text-align-last: center; color:white; font-size: 14pt;"><?//= $model->doc_name?></span>-->
	<?php if($model->doc_type2 === 'combine'){?>
		<table class="items" width="100%" style="font-family: helvetica; font-size: 10pt; border-collapse: collapse; margin-bottom: 10cm;" cellpadding="8">
		<?php foreach($model->item as $key=>$item) { ?>

				<thead>
						<tr>
							<td colspan="8" align="left" style="background-color: #ddd; border: 1px; padding: 0.5%; text-align-last: center; color:white; font-size: 14pt;"><?= $key+1?>. <?= $item->linkQuotation->doc_no?> - <?= $model->linkQuotation->doc_name?></td>
						</tr>
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

				<tbody>
					<!-- ITEMS HERE -->
					<?php $total = 0;?>
					<?php $count = 0;?>
					<?php $total_disc = 0;?>
					<?php $total_before_disc = 0;?>
					<?php 
					$childItems = \common\models\QuotationItem::find()->where(['quotation_id'=>$item->linkQuotation->id, 'active'=>1, 'product_parent_id'=>0])->all();
					?>
					<?php foreach($childItems as $childItem):?>
					<tr style="border-bottom: 1px solid #000000 !important;">
						<td style="text-align: center;"><?= ++$count?></td>
						<td style="text-align: center;"><?= $childItem->brand_name?></td>
						<td style="text-align: center;" >
							<img src="<?= $childItem->getImageUrl()?>" width="50"/>
						</td>
						<td style="text-align: center;"><?= $childItem->code?></td>
						<td>
							<b><?= $childItem->name?></b><br/><?= $childItem->description?>
						</td>
						<td align="center"><?= $childItem->quantity?></td>
						<td align="right"><?= number_format($childItem->retail_base_price_after_disc,2)?></td>
						<td align="right"><?= number_format($childItem->price_after_disc,2)?></td>
					</tr>
					<?php if($childItem->product_type=='service_package'):?>
					<tr>
						<td></td>
						<td colspan=7>
						<?php
							$packageItems = \common\models\QuotationItem::find()->where(['quotation_id'=>$childItem->quotation_id,'active'=>1, 'product_parent_id'=>$childItem->id])->andWhere(['<>','product_parent_id', 0])->all();
						?>
							<b>Comprising:</b><br/>
							<?php foreach($packageItems as $packageItem):?>
								<p><b><?= $packageItem->code?></b> | <?= $packageItem->name?></p>
							<?php endforeach;?>
						</td>
						
					</tr>	
					<?php endif;?>
					<?php endforeach;?>
					<tr>
						<td class="blanktotal" colspan="6" ></td>
						<td class="totals" align="left"><b>TOTAL (RM):</b></td>
						<td class="totals total" align="right" style="background-color: rgb(216, 252, 219) !important;"><b><?= number_format($model->total_price_after_disc, 2)?></b></td>
					</tr>
				</tbody>
		<?php } ?>
		</table>
	<?php } else { ?>
	<table class="items" width="100%" style="font-family: helvetica; font-size: 10pt; border-collapse: collapse; margin-bottom: 10cm;" cellpadding="8">
		<thead>
		        <tr>
					<td colspan="8" style="background-color: #fff; text-align:left; border: 0, text-decoration: underline; font-weight: bold; font-size: 14pt;"><span style='text-decoration: underline; font-family: sans-serif; font-size: 16pt;'>RE: <?= $model->doc_title?></span></td>
				</tr>
				<tr>
					<td colspan="5" style="background-color: rgb(129, 209, 193); border: 1px; padding: 0.5%; text-align-last: center; color:white; font-size: 14pt;"><?= $model->doc_name?></td>
				</tr>
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
		<tbody>
			<!-- ITEMS HERE -->
			<?php $total = 0;?>
			<?php $count = 0;?>
			<?php $total_disc = 0;?>
			<?php $total_before_disc = 0;?>
			<?php foreach($model->item as $item):?>
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
			<tr style="border-bottom: 1px solid #000000 !important;">
				<td style="text-align: center;"><?= ++$count?></td>
				<td style="text-align: center;"><?= $item->brand_name?></td>
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
			<?php if($item->product_type=='service_package'):?>
			<tr>
				<td></td>
				<td colspan=7>
				<?php
					$childItems = \common\models\QuotationItem::find()->where(['quotation_id'=>$item->quotation_id,'active'=>1, 'product_parent_id'=>$item->id])->andWhere(['<>','product_parent_id', 0])->all();
				?>
					<b>Comprising:</b><br/>
					<?php foreach($childItems as $childItem):?>
						<p><b><?= $childItem->code?></b> | <?= $childItem->name?></p>
					<?php endforeach;?>
				</td>
				
			</tr>	
			<?php endif;?>
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
				<td class="totals total" align="right" style="background-color: rgb(216, 252, 219) !important;"><b><?= number_format($model->total_price_after_disc, 2)?></b></td>
			</tr>
		</tbody>
	</table>
	<?php }?>
<br/>
<table width="100%" style="font-size: 7pt;" >
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
	</tbody>
</table>
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

<style>
body {font-family: helvetica;
            font-size: 7pt;
        }
        p {	margin: 0pt; }
        table.items {
            /*border: 1px dotted #000000;*/
        }
        td { vertical-align: top; font-family: helvetica; }
        .items td {
            border-left: 1px solid #000000;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
        }
        table thead td { 
            background-color: rgb(214, 252, 208);
            text-align: center;
            border: 1px solid #000000;
            font-variant: small-caps;
        }
        .items td.blanktotal {
            background-color: #EEEEEE;
            /*border: 1px solid #000000;*/
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 1px solid #000000;
            border-bottom: 1px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            background-color: rgb(242, 242, 242);
        }
        .items td.cost {
            text-align: "." center;
        }
        .items td.total {
            background-color: rgb(216, 252, 219) !important;
        }
	tr {
		border-bottom: 1px solid #000000;
	}
/*@page {
        margin-top: 2.5cm;
        margin-bottom: 2.5cm;
        margin-left: 2cm;
        margin-right: 2cm;
        footer: html_letterfooter2;
        background-color: pink;
    }

 @page letterhead {
        margin-top: 2.5cm;
        margin-bottom: 2.5cm;
        margin-left: 2cm;
        margin-right: 2cm;
        footer: html_letterfooter2;
        background-color: pink;
    }*/

</style>

<htmlpagefooter name="letterfooter2">
	<br/>
	<?php if(is_object($model->approvedBy) && $model->approvedBy->name):?>
	<p>Approved By:</p>
	<p><?= $model->approvedBy->name?></p>
	<p><img src="<?= $model->approvedBy->getImageUrl()?>" width="100" height="50"/></p>
	<?php endif;?>
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; font-family: sans-serif; ">
        Page {PAGENO} of {nbpg}
    </div>
</htmlpagefooter>
