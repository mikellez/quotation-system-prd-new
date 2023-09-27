<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\PointLedger */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="point-ledger-form">

    <?php $form = ActiveForm::begin(['id'=>'point-ledger-form']); ?>

    <?= $form->field($model, 'user_id_from')->dropDownList(
        $model->getUserList(),
        [
            'readOnly'=>true
        ]
    )
    ?>

    <?= $form->field($model, 'user_id_to')->dropDownList(
        $model->getUserList(Yii::$app->user->id)
    )
    ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit')->textInput(['type'=>'number', 'min'=>0.00, 'maxlength' => true]) ?>


    <div class="form-group">
        <!--<?//= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>-->
        <?= Html::button('Save', [
            'class' => 'btn btn-success showModalButton',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#modal',
            'value'=> Yii::$app->urlManager->createUrl(['user/authentication', 'id'=>Yii::$app->user->id])
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php 
        Modal::begin([
            'title' => 'Authentication',
            'id' => 'modal',
            'size' => 'modal-s',
        ]);

        echo "<div id='modalContent'><div class='spinner-border' role='status'> <span class='sr-only'>Loading...</span> </div></div>";

        Modal::end();
    
    ?>

</div>
