<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Login';
?>

<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Quotation</b>SYSTEM</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{input}"
            ],
        ]); ?>  



        <?= $form->field($model, 'username', [
            'options'=>[
                'class'=>'input-group mb-3',
            ],
            'template'=>'
                {input}
                <div class="input-group-append"> 
                    <div class="input-group-text"> 
                        <span class="fas fa-envelope"></span> 
                    </div> 
                </div>
            '
        ])->textInput([ 'autofocus' => true, 'placeholder'=>'Username']) ?>

        <?= $form->field($model, 'password', [
            'options'=>[
                'class'=>'input-group mb-3',
            ],
            'template'=>'
                {input}
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
            '
        ])->passwordInput([ 'placeholder'=>'Password' ]) ?>

        <div class="row">
            <div class="col-8">
                <?= $form->field($model, 'rememberMe', [
                    'options'=>[
                        'class'=>'icheck-primary'
                    ]
                ])->checkbox() ?>
            </div>
            <div class="col-4">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>
        </div>

        <!--<p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
            <a href="register.html" class="text-center">Register a new membership</a>
        </p>-->


        <?php ActiveForm::end(); ?>

    
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->