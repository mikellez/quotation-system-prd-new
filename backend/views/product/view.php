<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title)?></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            </p>
            <div class="row mt-10">
                <div class="col-md-6">
                  <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">General Information</h3>
                    </div>
                    <div class="card-body">
                      <?= DetailView::widget([
                          'model' => $model,
                          'attributes' => [
                              'id',
                              'name',
                              'brand_name',
                              /*[
                                  'attribute'=>'type',
                                  'value'=>$model->getType()->one()->type
                              ],*/
                              'code',
                              [
                                  'attribute' => 'image',
                                  'format' => ['html'],
                                  'value' =>    function() use ($model) {
                                      return Html::img($model->getImageUrl(), ['style' => 'width: 50px']);
                                  },

                              ],
                              'description:html',
                              [
                                  'attribute' => 'status',
                                  'format' => ['html'],
                                  'value' =>    function() use ($model) {
                                      return Html::tag('span', $model->status ? 'Active' : 'Draft', [
                                          'class' => $model->status ? 'badge badge-success' : 'badge badge-danger'
                                      ]);
                                  },

                              ],
                              'created_at:datetime',
                              'updated_at:datetime',
                              'createdBy.username',
                              'updatedBy.username',
                          ],
                      ]) ?>

                    </div>
                  </div> 
                </div>
                <div class="col-md-6">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title">Retail Information</h3>
                    </div>
                    <div class="card-body">
                      <?= DetailView::widget([
                          'model' => $model,
                          'attributes' => [
                              [
                                'attribute' => 'agent_comm',
                                'format' => ['decimal', 2]
                              ],
                              [
                                'attribute' => 'retail_base_price',
                                'format' => ['decimal', 2]
                              ],
                              [
                                'attribute' => 'standard_costing',
                                'format' => ['decimal', 2]
                              ],
                              [
                                'attribute' => 'threshold_discount',
                                'format' => ['decimal', 2]
                              ],
                              [
                                'attribute' => 'admin_discount',
                                'format' => ['decimal', 2]
                              ]
                          ],
                      ]) ?>
                    </div>
                  </div> 
                  <div class="card card-info">
                    <div class="card-header">
                      <h3 class="card-title">General Information</h3>
                    </div>
                    <div class="card-body">
                      <?= DetailView::widget([
                          'model' => $model,
                          'attributes' => [
                              'projectCurrency.currency',
                              [
                                'attribute' => 'project_base_price',
                                'format' => ['decimal', 2]
                              ],
                              [
                                'attribute' => 'project_threshold_discount',
                                'format' => ['decimal', 2]
                              ]
                          ],
                      ]) ?>

                    </div>
                </div>
          </div>
            
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
        </div>

</div>
