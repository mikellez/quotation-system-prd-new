<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductComponent */

$this->title = 'Update Product Component: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Product Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-component-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
