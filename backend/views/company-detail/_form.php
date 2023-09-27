<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\CompanyDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-detail-form">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'companyName')->textInput(['disabled'=>'disabled']) ?>

            <?= $form->field($model, 'payment_tnc')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'basic'
            ]) ?>

            <!--<?= $form->field($model, 'created_by')->textInput() ?>-->

            <!--<?= $form->field($model, 'created_at')->textInput() ?>-->

            <!--<?= $form->field($model, 'updated_by')->textInput() ?>-->

            <!--<?= $form->field($model, 'updated_at')->textInput() ?>-->

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-sm btn-success float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        
    </div>

    </div>

</div>
