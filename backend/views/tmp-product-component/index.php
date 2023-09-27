<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpProductComponentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tmp Product Components';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-product-component-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tmp Product Component', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'products_id',
            'product_component_id',
            'qty',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
