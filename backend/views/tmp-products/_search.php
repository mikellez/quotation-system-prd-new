<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\TmpProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tmp-products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'brand_name') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'project_currency') ?>

    <?php // echo $form->field($model, 'retail_base_price') ?>

    <?php // echo $form->field($model, 'project_base_price') ?>

    <?php // echo $form->field($model, 'threshold_discount') ?>

    <?php // echo $form->field($model, 'project_threshold_discount') ?>

    <?php // echo $form->field($model, 'admin_discount') ?>

    <?php // echo $form->field($model, 'standard_costing') ?>

    <?php // echo $form->field($model, 'agent_comm') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'product_type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
