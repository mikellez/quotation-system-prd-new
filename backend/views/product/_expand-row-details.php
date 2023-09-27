
<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use backend\components\Alert;

use common\models\search\ProductComponentSearch;
?>

<?php

   $bordered =1;
   $striped=0;
   $condensed=1;
   $responsive=1;
   $hover=0;
   $pageSummary=1;
   $heading="<h4>$this->title</h4>";
   $exportConfig=0;

    $searchModel = new ProductComponentSearch();
	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	$dataProvider->query->andWhere(['products_id'=>$model->id]);
	//$dataProvider->query->andWhere(['product_parent_id'=>$model->id]);

?>
<div class="card">
  <div class="card-header">
    Component:
  </div>
  <div class="card-body">
	<table class="table">
		<?php $i=0;?>
		<?php foreach($dataProvider->models as $m):?>
      <?php $childProduct = \common\models\Product::findOne($m->product_component_id)?>
			<tr>
				<td><?= ++$i;?></td>
				<td><?= $childProduct->code?></td>
				<td><?= $childProduct->name?></td>
			</tr>
		<?php endforeach;?>
	</table>
  </div>
</div>
