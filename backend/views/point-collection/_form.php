<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PointDoc */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="point-doc-form">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'doc_no')->textInput(['maxlength' => true, 'readOnly'=>true]) ?>

            <?= $form->field($model, 'total_payment_received')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
