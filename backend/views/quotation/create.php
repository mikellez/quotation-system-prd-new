<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Quotation */

$this->title = 'Create Quotation';
$this->params['breadcrumbs'][] = ['label' => 'Quotations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-create">

    <?php if(!empty($isSlave)):?>
    <h4>Project Quotation: <?= $modelQuotationMaster->doc_no?></h4>
    <?php endif;?>

    <?= $this->render('_form', [
        'model' => $model,
        'clientModel' => $clientModel,
        'isSlave'=> $isSlave ?? false,
    ]); ?>

</div>
