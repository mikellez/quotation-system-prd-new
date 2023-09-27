<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'readOnly'=>true] ) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true] ) ?>

<div class="form-group">
	<p id="error"></p>
	<table>
		<tr>
			<td>User Balance: </td>
			<td><span id="user_balance"></span></td>
		</tr>
		<tr>
			<td>Transfer Point: </td>
			<td><span id="transfer_point"></span></td>
		</tr>
		<tr>
			<td>Balance: </td>
			<td><span id="balance"></span></td>
		</tr>
	</table>
</div>

<div class="form-group">
	<?= Html::button('Check Balance', [ 'class' => 'btn btn-warning', 'id' => 'check-submit']) ?>
	<?= Html::button('Save', [ 'class' => 'btn btn-success float-right', 'id' => 'password-submit']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$url = Yii::$app->urlManager->createUrl(['user/authentication', 'id'=>Yii::$app->user->id]);

$js = <<<JS
    $("#check-submit").on('click', function(){

        $.ajax({
            type: 'POST',
            url: '$url',
			dataType: 'json',
            data: { 
				'LoginForm[username]' :  $("input[name='LoginForm[username]']").val(),
				'LoginForm[password]' :  $("input[name='LoginForm[password]']").val(),
				'credit': $("#pointledger-credit").val()
			},
            success: function(data) {
				console.log(data.msg);
				//$("form").html(data);
				if(data.success) {
					$("#user_balance").html(data.user_balance);
					$("#user_balance").addClass('text-success');
					$("#transfer_point").html(data.transfer_point);
					$("#transfer_point").addClass('text-success');
					$("#balance").html(data.balance);

					if(data.balance < 0 || $("#pointledger-credit").val() == 0) {
						$("#transfer_point").addClass('text-danger');
						$("#balance").addClass('text-danger');
						return false;
					}

					$("#transfer_point").addClass('text-success');


				} else {
					$("#error").html("<span class='text-danger'>"+data.msg+"</span>");
					return false;
				}

            }
        });

        return false;
    });

    $("#password-submit").on('click', function(){

        $.ajax({
            type: 'POST',
            url: '$url',
			dataType: 'json',
            data: { 
				'LoginForm[username]' :  $("input[name='LoginForm[username]']").val(),
				'LoginForm[password]' :  $("input[name='LoginForm[password]']").val(),
				'credit': $("#pointledger-credit").val()
			},
            success: function(data) {
				console.log(data.msg);
				//$("form").html(data);
				if(data.success) {
					$("#user_balance").html(data.user_balance);
					$("#user_balance").addClass('text-success');
					$("#transfer_point").html(data.transfer_point);
					$("#transfer_point").addClass('text-success');
					$("#balance").html(data.balance);

					if(data.balance < 0 || $("#pointledger-credit").val() == 0) {
						$("#error").html("<span class='text-danger'>Insufficient balance</span>");
						$("#transfer_point").addClass('text-danger');
						$("#balance").addClass('text-danger');
						return false;
					}

					$("#transfer_point").addClass('text-success');

					$("#point-ledger-form").submit();

				} else {
					$("#error").html("<span class='text-danger'>"+data.msg+"</span>");
					return false;
				}

            }
        });

        return false;
    });

JS;

$this->registerJs($js, $this::POS_END);
?>