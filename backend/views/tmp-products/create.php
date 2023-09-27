<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TmpProducts */

$this->title = 'Create Tmp Products';
$this->params['breadcrumbs'][] = ['label' => 'Tmp Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
