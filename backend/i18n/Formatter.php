<?php
namespace backend\i18n;

class Formatter extends \yii\i18n\Formatter {
	public function asOrderStatus($status) {
		if($status == \common\models\Quotation::STATUS_APPROVE) {
			return \yii\bootstrap4\Html::tag('span', 'Approved', ['class' => 'badge badge-light']);
		} else if($status == \common\models\Quotation::STATUS_PENDING) {
			return \yii\bootstrap4\Html::tag('span', 'Pending', ['class' => 'badge badge-info']);
		} else if($status == \common\models\Quotation::STATUS_DRAFT) {
			return \yii\bootstrap4\Html::tag('span', 'Draft', ['class' => 'badge badge-dark']);
		} else if($status == \common\models\Quotation::STATUS_CANCEL) {
			return \yii\bootstrap4\Html::tag('span', 'Cancel', ['class' => 'badge badge-warning']);
		} else if($status == \common\models\Quotation::STATUS_REJECT) {
			return \yii\bootstrap4\Html::tag('span', 'Reject', ['class' => 'badge badge-danger']);
		} else if($status == \common\models\Quotation::STATUS_DONE) {
			return \yii\bootstrap4\Html::tag('span', 'Done', ['class' => 'badge badge-success']);
		} else if($status == \common\models\Quotation::STATUS_CONFIRM) {
			return \yii\bootstrap4\Html::tag('span', 'Confirm', ['class' => 'badge badge-info']);
		}
	}
}
?>