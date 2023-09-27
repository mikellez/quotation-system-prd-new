<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <?= Html::a('Create Companies', ['create'], ['class' => 'btn btn-success btn-sm']) ?>            
        </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <?php $form = ActiveForm::begin([
                'options'=> [
                    'enctype' => 'multipart/form-data',
                ]

            ]); ?>

            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'imageFile')->textInput(['type' => 'file', 'style'=>'padding: 3px !important;']) ?>

            <?= $form->field($model, 'payment_tnc')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'basic'
            ])->label('Default Payment Tnc') ?>


            <!--<?= $form->field($model, 'created_by')->textInput() ?>-->

            <!--<?= $form->field($model, 'created_at')->textInput() ?>-->

            <!--<?= $form->field($model, 'updated_by')->textInput() ?>-->

            <!--<?= $form->field($model, 'updated_at')->textInput() ?>-->

            <?php 
                if($disableDetail==false) {
                    echo $this->render('/company-detail/index', [ 
                        'searchModel'=>$searchCompanyDetailModel,
                        'dataProvider'=>$dataProviderCompanyDetail
                    ]);
                }
            ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-sm btn-success float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        
        </div>

    </div>

</div>
