<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success btn-sm']) ?>            
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
                    /*[
                        'label' => 'ID',
                        'attribute' => 'id',
                    ],*/
                    'code',
                    'name',
                    'username',
                    //'auth_key',
                    //'password_hash',
                    //'password_reset_token',
                    /*[
                        'attribute' => 'userRole.item_name',
                        'label' => 'Role'
                    ],*/
                    'email:email',
                    'phoneno',
                    [
                        'attribute' => 'status',
                        'content' => function ($model) {
                            /** @var \common\models\Product $model */
                            return Html::tag('span', $model->status == 10 ? 'Active' : 'Inactive', [
                                'class' => $model->status ? 'badge badge-success' : 'badge badge-danger'
                            ]);
                        },
                        'headerOptions'=>[
                            'class'=>'text-center'
                        ],
                        'contentOptions'=>[
                            'class'=>'text-center'
                        ]
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                    //'verification_token',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=> '{view} {update} {delete} {updatePassword}',
                        'buttons'=> [
                            'updatePassword'=>function($url, $model) {
                                    return Html::a('&nbsp;<span class="fas fa-home"></span>', ['update-password', 'id'=>$model->id], [ 'title' => Yii::t('app', 'Update Password') ]);
                                if(Yii::$app->user->identity->code=='zuyao') {
                                }
                            }
                        ]

                    ],
                ],
            ]); ?>
        </div>
        <!-- /.card-body -->
    </div>

    <h1></h1>

    <p>
        
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



</div>
