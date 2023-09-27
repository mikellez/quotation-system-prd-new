<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PointLedgerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="point-ledger-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id_from') ?>

    <?= $form->field($model, 'user_id_to') ?>

    <?= $form->field($model, 'doc_id') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'ref_no') ?>

    <?php // echo $form->field($model, 'debit') ?>

    <?php // echo $form->field($model, 'credit') ?>

    <?php // echo $form->field($model, 'balance') ?>

    <?php // echo $form->field($model, 'accumulate_point') ?>

    <?php // echo $form->field($model, 'remark') ?>

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
