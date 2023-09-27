
<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
$readonly = $model->status != $model::STATUS_DRAFT ? true : false;
$readonlyArr = $readonly ? ['readonly'=>true] : [];
?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin(['id'=>'product-status-form']); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'doc_no')->textInput(['maxlength' => true, 'readonly'=>true]) ?>

    <?php if(Yii::$app->user->can("approve-quotation")):?>
    <?= $form->field($model, 'status')->dropDownList([
        \common\models\Quotation::STATUS_APPROVE => 'Approve',
        \common\models\Quotation::STATUS_REJECT => 'Reject'
    ]) ?>

    <?= $form->field($model, 'reason')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?php endif;?>

    <?php ActiveForm::end(); ?>

    <?= $this->renderAjax('product_status', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ])?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm float-right', 'onclick'=> 'document.getElementById("product-status-form").submit()']) ?>
    </div>

</div>
