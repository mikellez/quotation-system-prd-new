<?php
use dosamigos\chartjs\ChartJs;

?>

<div class="row">
	<div class="col-lg-4 col-6">
	<!-- small box -->
	<div class="small-box bg-info">
		<div class="inner">
		<h3><?= $quotation_issued?></h3>

		<p>Quotation issued (this month)</p>
		</div>
		<div class="icon">
		<i class="ion ion-bag"></i>
		</div>
		<!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
	</div>
	</div>
	<!-- ./col -->
	<div class="col-lg-4 col-6">
	<div class="small-box bg-warning">
		<div class="inner">
		<h3><?= $total_orders?></h3>

		<p>Total Orders</p>
		</div>
		<div class="icon">
		<i class="ion ion-person-add"></i>
		</div>
		<!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
	</div>
	</div>
	<!--<div class="col-lg-3 col-6">
	<div class="small-box bg-success">
		<div class="inner">
		<h3>53<sup style="font-size: 20px">%</sup></h3>

		<p>Point</p>
		</div>
		<div class="icon">
		<i class="ion ion-stats-bars"></i>
		</div>
	</div>
	</div>-->
	<!-- ./col -->
	<!--<div class="col-lg-3 col-6">
	<div class="small-box bg-warning">
		<div class="inner">
		<h3>44</h3>

		<p>Gross Margin</p>
		</div>
		<div class="icon">
		<i class="ion ion-person-add"></i>
		</div>
		<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
	</div>
	</div>-->
	<!-- ./col -->
	<div class="col-lg-4 col-6">
	<!-- small box -->
	<div class="small-box bg-danger">
		<div class="inner">
		<h3><?= number_format($total_profit,2)?></h3>

		<p>Profit</p>
		</div>
		<div class="icon">
		<i class="ion ion-pie-graph"></i>
		</div>
		<!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
	</div>
	</div>
	<!-- ./col -->
</div>

<div class="row">
	<div class="col-md-12">
	<div class="card">
		<div class="card-header">
		<h5 class="card-title">Monthly Recap Report</h5>

		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
			</button>
			<div class="btn-group">
			<button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
				<i class="fas fa-wrench"></i>
			</button>
			<div class="dropdown-menu dropdown-menu-right" role="menu">
				<a href="#" class="dropdown-item">Action</a>
				<a href="#" class="dropdown-item">Another action</a>
				<a href="#" class="dropdown-item">Something else here</a>
				<a class="dropdown-divider"></a>
				<a href="#" class="dropdown-item">Separated link</a>
			</div>
			</div>
			<button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
			</button>
		</div>
		</div>
		<!-- /.card-header -->
		<div class="card-body">
		<div class="row">
			<div class="col-md-12">
			<p class="text-center">
				<strong>Sales: <?= date('01/m/Y')?> - <?= date('t/m/Y')?></strong>
			</p>

			<!--<div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
				<canvas id="salesChart" height="360" style="height: 180px; display: block; width: 741px;" width="1482" class="chartjs-render-monitor"></canvas>
			</div>-->

			<?= ChartJs::widget([
				'type' => 'line',
				'data' => [
					'labels' => array_unique(array_merge(array_column($total_cost_byday, "date"), array_column($total_profit_byday, "date"), array_column($total_revenue_byday, "date"))),
					'datasets' => [
						[
							'label' => "Total Cost",
							'backgroundColor' => "rgba(179,181,198,0.2)",
							'borderColor' => "rgba(179,181,198,1)",
							'pointBackgroundColor' => "rgba(179,181,198,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(179,181,198,1)",
							'data' => array_column($total_cost_byday,"total_cost")
						],
						[
							'label' => "Total Profit",
							'backgroundColor' => "rgba(255,99,132,0.2)",
							'borderColor' => "rgba(255,99,132,1)",
							'pointBackgroundColor' => "rgba(255,99,132,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(255,99,132,1)",
							'data' => array_column($total_profit_byday, "total_profit")
						],
						[
							'label' => "Total Revenue",
							'backgroundColor' => "rgba(181,99,132,0.2)",
							'borderColor' => "rgba(181,99,132,1)",
							'pointBackgroundColor' => "rgba(181,99,132,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(181,99,132,1)",
							'data' => array_column($total_revenue_byday, "total_price")
						]
					]
				]
			]);
			?>
			<!-- /.chart-responsive -->
			</div>
		</div>
		<!-- /.row -->
		</div>
		<!-- ./card-body -->
		<div class="card-footer">
		<div class="row">
			<div class="col-sm-4 col-6">
			<div class="description-block border-right">
				<span class="description-percentage <?= $total_revenue_perc > 0 ? 'text-sucess' : ($total_revenue_perc < 0 ? 'text-danger' : 'text-warning')?>"><i class="fas <?= $total_revenue_perc > 0 ? 'fa-caret-up' : ($total_revenue_perc < 0 ? 'fa-caret-down' : 'fa-caret-left')?>"></i> <?= number_format(abs($total_revenue_perc),2)?>%</span>
				<h5 class="description-header">RM <?= number_format($total_revenue, 2)?></h5>
				<span class="description-text">TOTAL REVENUE</span>
			</div>
			<!-- /.description-block -->
			</div>
			<!-- /.col -->
			<div class="col-sm-4 col-6">
			<div class="description-block border-right">
				<span class="description-percentage <?= $total_cost_perc > 0 ? 'text-sucess' : ($total_cost_perc < 0 ? 'text-danger' : 'text-warning')?>"><i class="fas <?= $total_cost_perc > 0 ? 'fa-caret-up' : ($total_cost_perc < 0 ? 'fa-caret-down' : 'fa-caret-left')?>"></i> <?= number_format(abs($total_cost_perc),2)?>%</span>
				<h5 class="description-header">RM <?= number_format($total_cost, 2)?></h5>
				<span class="description-text">TOTAL COST</span>
			</div>
			<!-- /.description-block -->
			</div>
			<!-- /.col -->
			<div class="col-sm-4 col-6">
			<div class="description-block border-right">
				<span class="description-percentage <?= $total_profit_perc > 0 ? 'text-sucess' : ($total_profit_perc < 0 ? 'text-danger' : 'text-warning')?>"><i class="fas <?= $total_profit_perc > 0 ? 'fa-caret-up' : ($total_profit_perc < 0 ? 'fa-caret-down' : 'fa-caret-left')?>"></i> <?= number_format(abs($total_profit_perc),2)?>%</span>
				<h5 class="description-header">RM <?= number_format($total_profit,2)?></h5>
				<span class="description-text">TOTAL PROFIT</span>
			</div>
			<!-- /.description-block -->
			</div>
			<!-- /.col -->
			<!--<div class="col-sm-3 col-6">
			<div class="description-block">
				<span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
				<h5 class="description-header">1200</h5>
				<span class="description-text">GOAL COMPLETIONS</span>
			</div>-->
			<!-- /.description-block -->
			</div>
		</div>
		<!-- /.row -->
		</div>
		<!-- /.card-footer -->
	</div>
	<!-- /.card -->
	</div>
	<!-- /.col -->
</div>

<div class="row">
	<div class="col-8">
		<div class="card">
		<div class="card-header border-transparent">
			<h3 class="card-title">Latest Orders</h3>

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
		<div class="card-body p-0">
			<div class="table-responsive">
			<table class="table m-0">
				<thead>
				<tr>
				<th>Quotation ID</th>
				<th>Status</th>
				<th>Total</th>
				<th>Date</th>
				</tr>
				</thead>
				<tbody>
				<?php $total = 0;?>
				<?php foreach($quotations as $quotation):?>
					<?php foreach($quotation->item as $item):?>
						<?php 
						$qty = $item->quantity;
						$price = $item->retail_base_price;
						$amt = $qty * $price;
						$discount = $item->discount/100;
						$discount2 = $item->discount2/100;
						?>
						<?php $subtotal = $amt - ($amt * $discount) - ($amt * $discount2); ?>
						<?php $total += $subtotal;?>

					<?php endforeach;?>
					<?php $total = $quotation->total_price_after_disc?>
				<tr>
				<td><a href="<?= Yii::$app->urlManager->createUrl(['quotation/document', 'id'=>$quotation->id])?>"><?= $quotation->doc_no?></a></td>
				<td><span class="badge badge-success">Done</span></td>
				<td>
					<div class="sparkbar" data-color="#00a65a" data-height="20"><?= number_format($total,2)?></div>
				</td>
				<td><?= date('d/m/Y', $quotation->updated_at)?></td>
				</tr>
				<?php endforeach;?>
				</tbody>
			</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.card-body -->
		<div class="card-footer clearfix">
			<a href="<?= Yii::$app->urlManager->createUrl('quotation/create')?>" class="btn btn-sm btn-info float-left">Create Quotation</a>
			<a href="<?= Yii::$app->urlManager->createUrl('quotation')?>" class="btn btn-sm btn-secondary float-right">View All Quotation</a>
		</div>
		<!-- /.card-footer -->
		</div>
	</div>

	<div class="col-4">
		<div class="card">
		<div class="card-header">
			<h3 class="card-title">Recently Added Products</h3>

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
		<div class="card-body p-0">
			<ul class="products-list product-list-in-card pl-2 pr-2">
			<?php foreach($products as $product):?>
			<li class="item">
				<div class="product-img">
				<img src="<?= $product->getImageUrl()?>" alt="Product Image" class="img-size-50">
				</div>
				<div class="product-info">
				<a href="javascript:void(0)" class="product-title"><?= $product->name?>
					<span class="badge badge-info float-right">RM <?= number_format($product->retail_base_price,2)?></span></a>
				<span class="product-description">
					<?= $product->description?>
				</span>
				</div>
			</li>
			<?php endforeach;?>
			<!-- /.item -->
			</ul>
		</div>
		<!-- /.card-body -->
		<div class="card-footer text-center">
			<a href="<?= Yii::$app->urlManager->createUrl('product')?>" class="uppercase">View All Products</a>
		</div>
		<!-- /.card-footer -->
		</div>
	</div>
</div>
