<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TmpProductComponent */

$this->title = 'Create Tmp Product Component';
$this->params['breadcrumbs'][] = ['label' => 'Tmp Product Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-product-component-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
