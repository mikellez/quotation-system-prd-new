<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ]
            ]); ?>
            <?= $form->errorSummary($model) ?>
            <?= $form->errorSummary($authAssignmentModel) ?>

            <?= $form->field($model, 'code')->textInput() ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($authAssignmentModel, 'role')->dropDownList(
                $authAssignmentModel->getUserRoleList(),
                ['prompt' => '-- Select Role --'],
            ) ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'phoneno')->textInput() ?>

            <?= $form->field($model, 'company')->dropDownList(
                $companyModel->getCompanyList(),
                ['prompt' => '-- Select Company --']
            ) ?>

            <?= $form->field($model, 'signatureImageFile', [
                'template' => '
                    <label>Signature Image</label> 
                    <div class="">
                        <div class="custom-file">
                            {input}
                            {label}
                            {error}
                        </div>
                    </div>
                ',
                'labelOptions'=> ['class' => 'custom-file-label'],
                'inputOptions' => ['class' => 'custom-file-input']
            ])->textInput(['type' => 'file']) ?>

            <!--<?//= $form->field($model, 'status')->checkbox() ?>-->

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
