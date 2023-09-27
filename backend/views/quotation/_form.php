<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
$readonly = $model->status != $model::STATUS_DRAFT && !is_null($model->status) ? true : false;
$readonly = $isSlave ? true : $readonly;
$readonlyArr = $readonly ? ['readonly'=>true] : [];

?>

<div class="quotation-form">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->errorSummary($model) ?>

            <!--<?//= $form->field($model, 'doc_no')->textInput(['maxlength' => true]) ?>-->

            <!--<?//= $form->field($model, 'status')->checkbox() ?>-->

            <!--<?//= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>-->

            <?= $form->field($model, 'client')->dropDownList(
                $model->getClientList(),
                [
                    'prompt' => '-- Select Client --',
                    'onchange' => '
                        //var company_ele = document.getElementById("'.Html::getInputId($model, 'company').'");
                        var code_ele = document.getElementById("'.Html::getInputId($model, 'code').'");
                        var address_ele = document.getElementById("'.Html::getInputId($model, 'address').'");
                        var person_ele = document.getElementById("'.Html::getInputId($model, 'person').'");
                        var email_ele = document.getElementById("'.Html::getInputId($model, 'email').'");
                        var telephone_ele = document.getElementById("'.Html::getInputId($model, 'telephone').'");
                        var mobile_ele = document.getElementById("'.Html::getInputId($model, 'mobile').'");
                        var paymenttnc_ele = document.getElementById("'.Html::getInputId($model, 'payment_tnc').'")

                        if(this.value=="") {
                            //company_ele.value = "";
                            code_ele.value = "";
                            address_ele.innerHTML = "";
                            person_ele.value = "";
                            email_ele.value = "";
                            telephone_ele.value = "";
                            mobile_ele.value = "";
                        } else {
                            $.get( "'.Yii::$app->params['apiUrl'].'/clients/"+this.value, function( data ) {
                                //company_ele.value = data.company;
                                code_ele.value = data.code;
                                address_ele.innerHTML = data.address;
                                person_ele.value = data.person;
                                email_ele.value = data.email;
                                telephone_ele.value = data.telephone;
                                mobile_ele.value = data.mobile;
                            });
                        }

                    '
                ] + $readonlyArr
            ) ?>

            <!--<?//= $form->field($model, 'company')->textInput($readonlyArr) ?>-->
            <?= $form->field($model, 'company')->dropDownList(
                $model->getCompanyList(),
                [
                    'prompt' => '-- Select Company --',
                    'onchange' => '
                        var payment_tnc_id = "'.Html::getInputId($model, 'payment_tnc').'";
                        var payment_tnc_ele = document.getElementById("'.Html::getInputId($model, 'payment_tnc').'");
                        var dummy_payment_tnc_ele = document.getElementById("'.Html::getInputId($model, 'dummy_payment_tnc').'");

                        dummy_payment_tnc_ele.addEventListener( "change", function() {
                            CKEDITOR.instances["quotation-payment_tnc"].setData(this.value);
                        }, false);

                        if(this.value=="") {
                            payment_tnc_ele.value = "";
                        } else {
                            $.get( "'.Yii::$app->params['apiUrl'].'/companies/"+this.value, function( data ) {
                                payment_tnc_ele.value = data.payment_tnc;
                            });

                            $.get( "'.Yii::$app->params['apiUrl'].'/company-details/company?id="+this.value, function( data ) {

                                // Clear the old options
                                //dummy_payment_tnc_ele.options.length = 0;

                                //dummy_payment_tnc_ele.options.add(new Option("-- Select Payment Tnc --", ""));

                                var content = "";

                                for (const [key, value] of Object.entries(data)) {
                                    //dummy_payment_tnc_ele.options.add(new Option(value, value));

                                    content += `<div class="card card-outline card-warning col-3 mr-1">
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            ${value}
                                            <input type="radio" name="payment_tnc" value="${value}" onchange="var paymenttnc_ele = CKEDITOR.instances[\'${payment_tnc_id}\'].setData(this.value)"/>
                                        </div>
                                    </div>`;
                                    
                                }

                                dummy_payment_tnc_ele.innerHTML = content;

                            });


                        }

                    '
                ] + $readonlyArr
            ) ?>

            <?= $form->field($model, 'code')->textInput($readonlyArr) ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 6] + $readonlyArr) ?>

            <?= $form->field($model, 'person')->textInput(['maxlength' => true] + $readonlyArr) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true] + $readonlyArr) ?>

            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true] + $readonlyArr) ?>

            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true] + $readonlyArr) ?>

            <div class="form-group">
                <label for="quotation-payment_tnc">Choose payment tnc</label>
                <div id="quotation-dummy_payment_tnc" class="row">
                    <?php foreach($model->getPaymentTncList($model->company) as $tnc):?>
                    <div class="card card-outline card-warning col-3 mr-1">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <?= $tnc?> 
                            <input type="radio" name="payment_tnc" value="<?=$tnc?>" onchange="var paymenttnc_ele = CKEDITOR.instances['<?= Html::getInputId($model, 'payment_tnc')?>'].setData(this.value)"/>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>

            <!--<?/*= $form->field($model, 'dummy_payment_tnc')->dropDownList(
                [],//$model->getPaymentTncList(),
                [
                    'prompt' => '-- Select Payment Tnc --',
                    'onchange' => '
                        var paymenttnc_ele = CKEDITOR.instances["'.Html::getInputId($model, 'payment_tnc').'"].setData(this.value)

                    '
                ] 
            )*/ ?>-->

            <?= $form->field($model, 'payment_tnc')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'basic'
            ]) ?>

            <?= $form->field($model, 'doc_title')->textInput(['maxlength' => true]) ?>

            <?php if(Yii::$app->request->get('type') != 'combine'):?>
            <?= $form->field($model, 'dummy_doc_name')->dropDownList(
                [
                    "Master Bedroom"=>"Master Bedroom",
                    "Living Room"=>"Living Room"
                ],
                [
                    'prompt' => '-- Select Quotation Name --',
                    'onchange' => '
                    console.log(this.value);
                        var docname_ele = document.getElementById("'.Html::getInputId($model, 'doc_name').'");

                        docname_ele.value = this.value;

                    '
                ]
            ) ?>
            <?php endif;?>

            <?= $form->field($model, 'doc_name')->textInput(['maxlength' => true]) ?>

            <?php if(Yii::$app->user->can("approve-quotation")):?>
                <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>

                <?= $form->field($model, 'reason')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'basic'
                ]) ?>
            <?php endif;?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
