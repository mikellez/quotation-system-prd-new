<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TmpProducts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tmp-products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_currency')->textInput() ?>

    <?= $form->field($model, 'retail_base_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_base_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'threshold_discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_threshold_discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admin_discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'standard_costing')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent_comm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'product_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
