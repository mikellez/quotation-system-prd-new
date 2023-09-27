<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CompanyDetail */

$this->title = 'Create Company Detail';
$this->params['breadcrumbs'][] = ['label' => 'Company Details', 'url' => ['/company/update?id='.$model->company]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-detail-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
