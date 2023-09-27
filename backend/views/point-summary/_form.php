<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PointLedger */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="point-ledger-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id_from')->textInput() ?>

    <?= $form->field($model, 'user_id_to')->textInput() ?>

    <?= $form->field($model, 'doc_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ref_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'debit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'balance')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
