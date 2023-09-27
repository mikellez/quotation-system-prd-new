<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\QuotationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quotation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'quotation_id') ?>

    <?= $form->field($model, 'doc_no') ?>

    <?= $form->field($model, 'rev_no') ?>

    <?= $form->field($model, 'doc_name') ?>

    <?php // echo $form->field($model, 'doc_title') ?>

    <?php // echo $form->field($model, 'project_name') ?>

    <?php // echo $form->field($model, 'total_price') ?>

    <?php // echo $form->field($model, 'total_price_after_disc') ?>

    <?php // echo $form->field($model, 'max_total_price_after_disc') ?>

    <?php // echo $form->field($model, 'total_discount') ?>

    <?php // echo $form->field($model, 'total_discount2') ?>

    <?php // echo $form->field($model, 'total_discount_value') ?>

    <?php // echo $form->field($model, 'max_total_discount_value') ?>

    <?php // echo $form->field($model, 'accumulate_discount_rate') ?>

    <?php // echo $form->field($model, 'max_accumulate_discount_rate') ?>

    <?php // echo $form->field($model, 'client') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_by') ?>

    <?php // echo $form->field($model, 'status_at') ?>

    <?php // echo $form->field($model, 'master') ?>

    <?php // echo $form->field($model, 'slave') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'company') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'person') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'mobile') ?>

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
