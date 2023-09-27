<!--mpdf

<htmlpageheader name="letterheader">
	<img src="<?= Yii::$app->params['backendUrl'].'/storage'.$model->company0->image;?>"/>
<table width="100%" style="font-family: helvetica; font-size: 8pt;" cellpadding="10">
	<tr>
		<td width="65%" style="">
			<table width="100%" style=" color: #555555; font-family: helvetica;">
				<tbody>
					<tr>
						<td width="15%">To</td>
						<td>: <?= $model->client0->company?></td>
					</tr>
					<tr>
						<td>Add</td>
						<td>: <?= $model->address?></td>
					</tr>
					<tr>
						<td>Attn</td>
						<td>: <?= $model->person?></td>
					</tr>
					<tr>
						<td>e</td>
						<td>:</td>
					</tr>
					<tr>
						<td>Contact No</td>
						<td>: <?= $model->mobile?></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td width="35%" style="">
			<table width="100%">
				<tbody>
					<tr>
						<td colspan="2" align="center" style="font-size: 15pt; color: #555555; font-family: helvetica; background-color: rgb(129, 209, 193); border: 1px; height: 34px;">
						<b>	Quotation</b>
						</td>
					</tr>
					<tr>
						<td >Quo Ref. No</td>
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
</htmlpageheader>

<htmlpagefooter name="letterfooter2">
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; font-family: helvetica; ">
        Page {PAGENO} of {nbpg}
    </div>
</htmlpagefooter>
mpdf-->

<style>
    @page {
        margin-top: 2.5cm;
        margin-bottom: 2.5cm;
        margin-left: 2cm;
        margin-right: 2cm;
        footer: html_letterfooter2;
    }

    @page {
        margin-top: 9cm;
        margin-bottom: 4cm;
        header: html_letterheader;
    }
  
    @page letterhead {
        margin-top: 9cm;
        margin-bottom: 4cm;
        header: html_letterheader;
        resetpagenum: 1;
    }

    /*@page :first {
        margin-top: 9cm;
        margin-bottom: 4cm;
        header: html_letterheader;
    }
  
    @page letterhead :first {
        margin-top: 9cm;
        margin-bottom: 4cm;
        header: html_letterheader;
        resetpagenum: 1;
    }*/
    .letter {
        page-break-before: always;
        page: letterhead;
    }
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
