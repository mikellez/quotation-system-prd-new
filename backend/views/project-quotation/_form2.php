
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
        <!--<?//= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm float-right', 'onclick'=> 'document.getElementById("product-status-form").submit()']) ?>-->
        <?= Html::button('<i class="fas fa-save"></i> Save', ['id' => 'generate-quotation-btn', 'class' => 'btn btn-success btn-sm float-right']) ?>
    </div>

</div>

<?php
    $id = Yii::$app->request->get('id');

    $js = <<<JS
        $(document).on('click', '#generate-quotation-btn', function(){
            swal({
                title: 'Are you sure?',
                html: "You will be generating quotation, please allow pop-ups for this site. For more information, visit this <a href='https://support.google.com/chrome/answer/95472?hl=en&co=GENIE.Platform%3DDesktop'>link.</a>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    const content = swal.getContainer()
                    if (content) {
                        const swalTitle = content.querySelector('.swal2-title');
                        const swalContent = content.querySelector('.swal2-content');
                        const swalCancel = content.querySelector('.swal2-cancel');

                        if (swalTitle) {
                            swalTitle.textContent = 'Please wait..';
                        }
                        if (swalContent) {
                            swalContent.textContent = 'Do not close this modal, system is generating quotation..';
                        }
                        if (swalCancel) {
                            swalCancel.remove();
                        }
                    }
                    return fetch('generate?id=$id&status=bypass')
                    .then(response => {
                        console.log(response);
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                },
                allowOutsideClick: () => !swal.isLoading()
                }).then((response) => {
                    console.log(response);
                if (response.value.success) {
                    swal("Success..","Quotation generated, ref no: "+response.value.model.doc_no,"success")
                    .then(okay => {
                        if (okay) {
                            window.location.href = 'index';
                            window.open('document?id='+response.value.model.id, '_blank');
                        }
                    });
                } else {
                    swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+response.value.model.doc_no,"error")
                    .then(okay => {
                        if (okay) {
                            window.location.href = "index";
                        }
                    });
                }
            })

        });

    JS;

    $this->registerJs($js, $this::POS_END);

?>