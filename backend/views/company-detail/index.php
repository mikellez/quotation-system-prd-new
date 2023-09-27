<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CompanyDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-detail-index">

    <!--<h1><?//= Html::encode($this->title) ?></h1>-->

    <div class="card card-outline card-warning">
        <div class="card-header">
            <!--<h3 class="card-title"><?= Html::encode($this->title) ?></h3>-->

            <div class="card-tools">
                <?= Html::a('<i class="fas fa-plus"></i> Create details', ['/company-detail/create?id='.Yii::$app->request->get('id')], ['class' => 'btn btn-warning btn-sm']) ?>            
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'company',
                    [
                        'attribute'=>'payment_tnc',
                        'format'=>'html'
                    ],
                    //'created_by',
                    //'created_at',
                    //'updated_by',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons'=>[
                            'view'=> function($url,$model) {
                                return Html::a('<span class="fas fa-eye"></span>', ['/company-detail/view', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'View'),
                                ]);
                            },
                            'update'=> function($url,$model) {
                                return Html::a('<span class="fas fa-pencil-alt"></span>', ['/company-detail/update', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Update'),
                                ]);
                            },
                            'delete'=> function($url,$model) {
                                return Html::a('<span class="fas fa-trash"></span>', ['/company-detail/delete', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Delete'),
                                ]);
                            }
                        ]
                    ]
                ],
            ]); ?>

        </div>

    </div>

</div>
