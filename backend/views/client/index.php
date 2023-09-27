<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <?= Html::a('Create Client', ['create'], ['class' => 'btn btn-success btn-sm']) ?>            
        </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions'=> ['class'=>'table table-striped table-bordered table-sm'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    [
                        'attribute'=>'company',
                        'content'=>function($model) {
                            return empty($model->company) ? "<span class='text-danger'>(Not Set)</span>" : $model->company;
                        }
                    ],
                    [
                        'attribute'=>'address',
                        'content'=>function($model) {
                            return empty($model->address) ? "<span class='text-danger'>(Not Set)</span>" : $model->address;
                        }
                    ],
                    //'address:ntext',
                    [
                        'attribute'=>'person',
                        'content'=>function($model) {
                            return empty($model->person) ? "<span class='text-danger'>(Not Set)</span>" : $model->person;
                        }
                    ],
                    [
                        'attribute'=>'email',
                        'content'=>function($model) {
                            return empty($model->email) ? "<span class='text-danger'>(Not Set)</span>" : $model->email;
                        }
                    ],
                    //'telephone',
                    //'mobile',
                    //'created_at',
                    //'updated_at',
                    //'created_by',
                    //'updated_by',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
        <!-- /.card-body -->
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



</div>
