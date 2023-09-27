<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PointLedgerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Point Transfer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-ledger-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <?= Html::a('Create Point Transfer', ['create'], ['class' => 'btn btn-success btn-sm']) ?>            
        </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'user.username',
                    'accumulate_point',
                    'userIdFrom.username',
                    'userIdTo.username',
                    'balance',
                    //'action',
                    //'ref_no',
                    //'credit',
                    'remark',
                    //'created_by',
                    //'created_at',
                    //'updated_by',
                    //'updated_at',

                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>

    </div>
</div>
