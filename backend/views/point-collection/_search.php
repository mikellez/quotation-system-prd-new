<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PointDocSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="point-doc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'quotation_id') ?>

    <?= $form->field($model, 'user_id_from') ?>

    <?= $form->field($model, 'user_id_to') ?>

    <?= $form->field($model, 'doc_no') ?>

    <?php // echo $form->field($model, 'doc_type') ?>

    <?php // echo $form->field($model, 'ref_no') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'sales_point') ?>

    <?php // echo $form->field($model, 'total_sales_point') ?>

    <?php // echo $form->field($model, 'total_payment_received') ?>

    <?php // echo $form->field($model, 'total_debit_sales_point') ?>

    <?php // echo $form->field($model, 'bf') ?>

    <?php // echo $form->field($model, 'total_point') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_by') ?>

    <?php // echo $form->field($model, 'status_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
