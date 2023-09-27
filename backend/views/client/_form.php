<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'person')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
