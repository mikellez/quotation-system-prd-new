<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PointLedger */

$this->title = 'Create Point Transfer';
$this->params['breadcrumbs'][] = ['label' => 'Point Transfer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-ledger-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
