<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;

$gridColumnsExport = [
    [
        'label'=>'col_action',
        'content'=>function() {
            return 'UPDATE';
        }
    ],
    ['attribute'=>'id', 'label'=>'id'],
    ['attribute'=>'company', 'label'=>'company'],
    ['attribute'=>'payment_tnc', 'label'=>'payment_tnc'],
    ['attribute'=>'image', 'label'=>'image'],
];
?>
<div class="company-index">

    <div class="card card-outline card-primary">
        <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

        <div class="card-tools">
            <!--<?//= Html::a('Import', ['import'], ['class' => 'btn btn-primary btn-sm']) ?>-->
            <!--<?//= Html::a('Export', ['export'], ['class' => 'btn btn-info btn-sm']) ?>-->
            <?= Html::a('Create Companies', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            <?= Html::a('Import Companies', ['import'], ['class' => 'btn btn-warning btn-sm']) ?>
            <?php 
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumnsExport,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-outline-secondary btn-default'
                ]
            ])?>
            <br/><br/>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    'company',
                    [
                        'attribute'=>'payment_tnc',
                        'format'=>'html'
                    ],
                    'createdBy.username',
                    'created_at:datetime',
                    //'updated_by',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}'
                    ]
                ],
            ]); ?>

        </div>

    </div>

</div>
