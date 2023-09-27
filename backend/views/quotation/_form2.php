
<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
$readonly = $model->status != $model::STATUS_DRAFT ? true : false;
$readonlyArr = $readonly ? ['readonly'=>true] : [];
?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin(['id'=>'product-status-form']); ?>

    <?= $form->errorSummary($model) ?>
	
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'doc_no')->textInput(['maxlength' => true, 'readonly'=>true, 'style'=>'width: 200px;']) ?>
        </div>
        <div class="col-sm-6">
            <div class="form-group field-quotation-created_at">
            <label for="quotation-created_at">Date</label>
            <input type="text" id="quotation-created_at" class="form-control hasDatepicker" name="Quotation[created_at]" value="<?= date('d-m-Y', $model->created_at)?>" disabled="" style='width: 200px;'>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group field-quotation-created_by">
            <label for="quotation-created_by">PIC</label>
            <input type="text" id="quotation-created_by" class="form-control hasDatepicker" name="" value="<?= $model->createdBy->username?>" disabled="" style='width: 200px;'>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Approve transaction</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="approve-name" class="col-form-label">Name:</label>
                    <input type="text" class="form-control" id="sign-approve-name" disabled value="<?= Yii::$app->user->identity->username?>">
                </div>
                <div class="form-group">
                    <label for="approve-password" class="col-form-label">Password:</label>
                    <input type="password" class="form-control" id="sign-approve-password">
                </div>
            </div>
            <div class="modal-footer">
                <button id="sign-approve-transaction" type="button" class="btn btn-primary">Sign</button>
            </div>
            </div>
        </div>
    </div>

    <?php /*if(Yii::$app->user->can("approve-quotation")):?>
    <?= $form->field($model, 'status')->dropDownList([
        \common\models\Quotation::STATUS_APPROVE => 'Approve',
        \common\models\Quotation::STATUS_REJECT => 'Reject'
    ]) ?>

    <?= $form->field($model, 'reason')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?php endif;*/?>

    <?php ActiveForm::end(); ?>

    <?= $this->renderAjax('product_status', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'quotationModel' => $quotationModel,
    ])?>

    <div class="form-group">
        <!--<?//= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm float-right', 'onclick'=> 'document.getElementById("product-status-form").submit()']) ?>-->
        <?php if($quotationModel->status == $quotationModel::STATUS_PENDING):?>
        <?= Html::button('<i class="fas fa-save"></i> Save', ['id' => 'generate-quotation-btn', 'class' => 'btn btn-success btn-sm float-right']) ?>
        <?php endif;?>
    </div>


</div>


<?php
    $id = Yii::$app->request->get('id');
    $csrfParam = Yii::$app->request->csrfParam;
    $csrfToken = Yii::$app->request->getCsrfToken();

    $js = <<<JS

        $('#exampleModal').on('shown.bs.modal', function () {
            $('#recipient-name').trigger('focus')
        })

        $(document).on('click', '#generate-quotation-btn', function(){

            $('#exampleModal').modal({
                show: true
            });

        });

        $('#sign-approve-transaction').on('click', function() {
            $.post('sign-transaction', {
                'LoginForm[username]': $("#sign-approve-name").val(), 
                'LoginForm[password]': $("#sign-approve-password").val(),
                '$csrfParam': '$csrfToken'
            }, function(response){ 
                if(JSON.parse(response).success) {
                    alert(JSON.parse(response).message)

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

                } else {
                    alert(JSON.parse(response).message);
                    return;
                }
            });
        });

    JS;

    $this->registerJs($js, $this::POS_END);

?>
