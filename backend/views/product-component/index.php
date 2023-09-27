<?php

use common\models\TmpProducts;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CompanyDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service Package';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-component-index" style="display: <?= $model->product_type == "service_package" ? "block" : "none"?>">

    <!--<h1><?//= Html::encode($this->title) ?></h1>-->

    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

            <div class="card-tools">
                <?= Html::button('<i class="fas fa-plus"></i> Create Service Package', [
                    'value' => Yii::$app->urlManager->createUrl(['/product-component/select', 'id' => Yii::$app->request->get('id')]),
                    'class' => 'btn btn-warning btn-sm showModalButton',
                    'id' => 'BtnModalId',
                    'data-toggle'=> 'modal',
                    'data-target'=> '#modal'
                ])?>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?php 
                /*$session = Yii::$app->session;
                $model = new \common\models\TmpProducts();
                if($session['TmpProducts'])  {
                    echo $session['TmpProducts']['id'];
                    $model = \common\models\TmpProducts::findOne($session['TmpProducts']['id']);
                }
                $searchModel = new \common\models\search\TmpProductComponentSearch();
                $dataProvider = $searchModel->search([]);
                $dataProvider->query->andWhere(['products_id'=>$model->id ?? 0]);*/
                echo $model->scenario;
            
            ?>
            
            <?php if($model->scenario == TmpProducts::SCENARIO_CREATE):?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'company',
                    [
                        'attribute'=>'productComponent.brand_name',
                    ],
                    [
                        'attribute'=>'productComponent.name',
                    ],
                    [
                        'attribute'=>'productComponent.code',
                    ],
                    //'created_by',
                    //'created_at',
                    //'updated_by',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fas fa-trash-alt"></span>', ['#'], [
                                    'class' => 'pjax-delete-link',
                                    'delete-url' => $url,
                                    'pjax-container' => 'pjax-product-grid',
                            'clientOptions' => 'GET'
                                ]);
                            }
                        ],
                        'urlCreator' => function($action, $model, $key, $index) { 
                            if($action === "delete") {
                                $url =Yii::$app->urlManager->createUrl(['/product-component/tmp-delete','id' => $model->id]);
                                return $url;
                            }
                        },
                    ]
                ],
            ]); ?>
            <?php else:?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'company',
                    [
                        'attribute'=>'productComponent.brand_name',
                    ],
                    [
                        'attribute'=>'productComponent.name',
                    ],
                    [
                        'attribute'=>'productComponent.code',
                    ],
                    //'created_by',
                    //'created_at',
                    //'updated_by',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons'=>[
                            'view'=> function($url,$model) {
                                return Html::a('<span class="fas fa-eye"></span>', ['/product-component/view', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'View'),
                                ]);
                            },
                            'update'=> function($url,$model) {
                                return Html::a('<span class="fas fa-pencil-alt"></span>', ['/product-component/update', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Update'),
                                ]);
                            },
                            'delete'=> function($url,$model) {
                                return Html::a('<span class="fas fa-trash"></span>', ['/product-component/delete', 'id'=>$model->id], [
                                    'title' => Yii::t('app', 'Delete'),
                                ]);
                            }
                        ]
                    ]
                ],
            ]); ?>
            <?php endif;?>

        </div>

    </div>

</div>

<?php 
$js = <<<JS

    $('.pjax-delete-link').one('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('delete-url');
        var pjaxContainer = $(this).attr('pjax-product-grid');
        var result = confirm('Delete this item, are you sure?');                                
        if(result) {
            $.ajax({
                url: deleteUrl,
                type: 'post',
                error: function(xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            }).done(function(data) {
                //$('#pjax-product-grid').css('opacity', '0.5');
                //$('#pjax-product-grid').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                //$.pjax.reload({'container': '#pjax-product-grid', 'timeout': 5000});
                $("#pjax-product-grid").html(data)
            });
        }
    });

JS;

$this->registerJs($js, $this::POS_END);

?>