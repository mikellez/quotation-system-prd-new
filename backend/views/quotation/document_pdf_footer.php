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
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; font-family: sans-serif; ">
	Page {PAGENO} of {nbpg}
</div>