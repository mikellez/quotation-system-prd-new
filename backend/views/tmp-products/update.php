<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TmpProducts */

$this->title = 'Update Tmp Products: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tmp Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tmp-products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
