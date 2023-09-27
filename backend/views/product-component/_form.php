<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductComponent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-component-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'products_id')->textInput() ?>

    <?= $form->field($model, 'product_component_id')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
