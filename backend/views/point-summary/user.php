<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'user.username',
                    'balance',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [ 
                            'view'=> function($url,$model) {
                                return Html::a('<span class="fas fa-eye"></span>', ['index', 'user_id'=>$model->user_id], [
                                    'title' => Yii::t('app', 'View'),
                                ]);
                            }
                        ]
                    ],
                ],
            ]); ?>

        </div>

    </div>

</div>


