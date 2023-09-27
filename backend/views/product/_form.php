<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;
use dosamigos\ckeditor\CKEditor;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Products */
/* @var $form yii\widgets\ActiveForm */

$retailBasePriceId = Html::getInputId($model, 'retail_base_price');
$projectBasePriceId = Html::getInputId($model, 'project_base_price');
?>

<?php
    Modal::begin([
        'title' => 'Products',
        'id' => 'modal',
        'size' => 'modal-xl',
    ]);

    echo "<div id='modalContent'><div class='spinner-border' role='status'> <span class='sr-only'>Loading...</span> </div></div>";

    Modal::end();

?>

<div class="product-form">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body p-0">
                <div id="stepperForm" class="bs-stepper linear">
                    <div class="bs-stepper-header" role="tablist">
                        <!-- your steps here -->
                        <div class="step active" data-target="#general-information-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="general-information-part" id="general-information-part-trigger" aria-selected="true">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">General Information</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#retail-information-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="retail-information-part" id="retail-information-part-trigger" aria-selected="false">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Retail Information</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#project-information-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="project-information-part" id="project-information-part-trigger" aria-selected="false">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Project Information</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'enctype' => 'multipart/form-data',
                                'enableClientValidation' => true,
                                'class' => 'needs-validation',
                                //'onSubmit' => 'return false',
                                'novalidate' => 1
                            ]
                        ]); ?>
                        <?= $form->errorSummary($model) ?>
                        <!-- your steps content here -->
                        <div id="general-information-part" class="content active dstepper-block bs-stepper-pane" role="tabpanel" aria-labelledby="general-information-part-trigger">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'brand_name')->textInput(['maxlength' => true]) ?>

                            <!--<?/*= $form->field($model, 'type')->dropDownList(
                                $model->getTypeList(),
                                ['prompt' => '-- Select Type --']
                            ) */?>-->

                            <?= $form->field($model, 'code')->textInput() ?>

                            <?= $form->field($model, 'product_type')->dropDownList(
                                $model->getProductTypeList(),
                                [
                                    'prompt' => '-- Select Type --',
                                    'onchange' => '
                                        if(this.value=="service_package") {
                                            $(".product-component-index").attr("style", "display: block;");
                                        } else {
                                            $(".product-component-index").attr("style", "display: none;");
                                        }
                                    '
                                ] 
                            ) ?>

                            <?= $form->field($model, 'imageFile', [
                                'template' => '
                                    <label>Product Image</label> 
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

                            <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                                'options' => ['rows' => 6],
                                'preset' => 'basic'
                            ]) ?>

                            <?php
                            Pjax::begin( [
                                'id' => 'pjax-product-grid',
                                'timeout' => 5000, 
                                'enablePushState' => false, 
                                'clientOptions' => ['method' => 'GET']]
                            );?>

                            <?=  $this->render('/product-component/index', [ 
                                'model' => $model,
                                'searchModel'=>$searchProductComponentModel,
                                'dataProvider'=>$dataProviderProductComponent,
                            ]);
                            ?>

                            <?php Pjax::end(); ?>

                            <button class="btn btn-primary btn-next-form btn-sm">Next</button>
                        </div>
                        <div id="retail-information-part" class="content bs-stepper-pane" role="tabpanel" aria-labelledby="retail-information-part-trigger">
                            <?= $form->field($model, 'agent_comm', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->agent_comm)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>

                            <?= $form->field($model, 'retail_base_price', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->retail_base_price)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>

                            <?= $form->field($model, 'standard_costing', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->standard_costing)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>

                            <?= $form->field($model, 'threshold_discount', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->threshold_discount)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>

                            <?= $form->field($model, 'admin_discount', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->admin_discount)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>




                            <button class="btn btn-primary btn-previous-form btn-sm">Previous</button>
                            <button class="btn btn-primary btn-next-form btn-sm">Next</button>
                        </div>
                        <div id="project-information-part" class="content bs-stepper-pane" role="tabpanel" aria-labelledby="project-information-part-trigger">
                            <?= $form->field($model, 'project_currency')->dropDownList(
                                $model->getCurrencyList(),
                                ['prompt' => '-- Select Currency --']
                            ) ?>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        <strong>Retail base price</strong> same as <strong>project base price</strong>
                                </div>
                            </div>

                            <?= $form->field($model, 'project_base_price', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->project_base_price)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>

                            <?= $form->field($model, 'project_threshold_discount', ['inputOptions'=>['value' => Yii::$app->formatter->asDecimal($model->project_threshold_discount)]])->textInput([
                                'maxlength' => true,
                                'type' => 'number'
                                ]) ?>

                            <?= $form->field($model, 'status')->checkbox() ?>

                            <button class="btn btn-primary btn-previous-form btn-sm">Previous</button>
                            <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-sm']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
<?php
$js = <<<JS
    var getDefaultCheck = document.getElementById('defaultCheck1');
    // Adding event listener change to each checkbox
    getDefaultCheck.addEventListener('change', function (event) {
        var retailBasePrice = document.getElementById("{$retailBasePriceId}");
        var projectBasePrice = document.getElementById("{$projectBasePriceId}");

        if (this.checked) {
            projectBasePrice.value = retailBasePrice.value;
        } else {
            projectBasePrice.value = '';
        }
    });

    var stepperFormEl = document.querySelector('#stepperForm', {
        linear: false
    });
    window.stepperForm = new Stepper(stepperFormEl);

    var btnNextList = [].slice.call(document.querySelectorAll('.btn-next-form'))
    var btnPrevList = [].slice.call(document.querySelectorAll('.btn-previous-form'))
    var stepperPanList = [].slice.call(stepperFormEl.querySelectorAll('.bs-stepper-pane'))
    var form = stepperFormEl.querySelector('.bs-stepper-content form')

    btnNextList.forEach(function (btn) {
        btn.addEventListener('click', function () {
            event.preventDefault();
            window.stepperForm.next()
        });
    });

    btnPrevList.forEach(function (btn) {
        btn.addEventListener('click', function () {
            event.preventDefault();
            window.stepperForm.previous()
        });
    });

  stepperFormEl.addEventListener('shown.bs-stepper', function (event) {
    form.classList.remove('was-validated');
    var nextStep = event.detail.indexStep;
    var currentStep = nextStep;

    if (currentStep > 0) {
      currentStep--;
    }

    var stepperPan = stepperPanList[currentStep];

    stepperPan.childNodes.forEach(function(i) {
    });
    if ((stepperPan.getAttribute('id') === 'test-form-1' && !inputMailForm.value.length) ||
    (stepperPan.getAttribute('id') === 'test-form-2' && !inputPasswordForm.value.length)) {
      event.preventDefault();
      form.classList.add('was-validated');
    }
  });
JS;

$this->registerJs($js, $this::POS_END);

?>