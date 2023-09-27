<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PointDoc */

$this->title = 'Create Point Doc';
$this->params['breadcrumbs'][] = ['label' => 'Point Docs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-doc-create">

    <!--<h1><?//= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
