<?php 

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use common\widgets\Alert;
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Saved!</h4>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="product-import-form">
	<?php
		$form = ActiveForm::begin([
			'id' => 'form-import',
			'type' => ActiveForm::TYPE_VERTICAL,
			'options'=> ['enctype'=>'multipart/form-data']
		]);

		echo Form::widget([
			'model'=> $model,
			'form'=> $form,
			'columns'=>2,
			'attributes'=>[
				'importFile'=> ['type'=> Form::INPUT_FILE, 'options'=> ['placeholder'=>'File Import Data'], 'columnOptions'=> ['colspan'=> 5]],
			]
		]);

	
	?>

	<div class="form-group">
		<?= Html::submitButton('Save', ['class'=>'btn btn-success']);?>
	</div>

	<?php ActiveForm::end();?>

	<?php
		$form = ActiveForm::begin([
			'id' => 'form-import',
			'type' => ActiveForm::TYPE_VERTICAL,
			'options'=> ['enctype'=>'multipart/form-data']
		]);

		echo $form->field($model, 'imageFile[]')->fileInput(['multiple' => true, 'accept' => 'image/*']);

	
	?>

	<div class="form-group">
		<?= Html::submitButton('Save', ['class'=>'btn btn-success']);?>
	</div>

	<?php ActiveForm::end();?>
</div>