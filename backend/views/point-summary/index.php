<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PointLedgerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Point Summary';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-ledger-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
        </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <p><?= $dataProvider->models[0]->user->username?></p>

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'toolbar' =>  [
                    '{export}',
                    '{toggleData}',
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'created_at:datetime',
                    //'remark',
                    'description',
					'docId.quotation.client0.person',
					'debit',
					'credit',
					'balance',
					'accumulate_point'
                ],
            ]); ?>

        </div>

    </div>

</div>


