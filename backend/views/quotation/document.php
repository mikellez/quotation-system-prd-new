    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!--<div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Note:</h5>
              This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
            </div>-->


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> <?= $model->company?>
                    <small class="float-right">Date: <?= date("d/m/Y", $model->created_at)?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>Admin, Sdn Bhd.</strong><br>
					          111, Jalan Shah Alam, Taman Shah Alam<br/>
                    Phone: 03-11111111<br>
                    Email: test@email.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong><?= $model->company?></strong><br>
					          <?= $model->address?><br/>
                    Phone: <?= $model->telephone?><br>
                    Email: <?= $model->email?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Quotation #<?= $model->doc_no?></b><br>
                  <br>
                  <b>Order ID:</b> <?= $model->id?><br>
                  <!--<b>Payment Due:</b> 2/22/2014<br>-->
                  <b>Account:</b> 968-34567
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Qty</th>
                      <th>Product</th>
                      <th>Brand</th>
                      <th>Description</th>
                      <th>Price</th>
					            <th>Disc</th>
					            <th>Admin Disc.</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php $total = 0;?>
						<?php foreach($model->item as $item):?>
            <?php 
              $qty = $item->quantity;
              $price = $item->retail_base_price;
              $amt = $qty * $price;
              $discount = $item->discount/100;
              $discount2 = $item->discount2/100;
            ?>
						<?php $subtotal = $amt - ($amt * $discount) - ($amt * $discount2); ?>
						<?php $total += $subtotal;?>
							<tr>
							<td><?= $item->quantity?></td>
							<td><?= $item->name?></td>
							<td><?= $item->brand_name?></td>
							<td><?= $item->description?></td>
							<td><?= $item->retail_base_price?></td>
							<td><?= $item->discount?></td>
							<td><?= $item->discount2?></td>
							<td><?= $subtotal?></td>
							</tr>
						<?php endforeach;?>

                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                <!--  <p class="lead">Payment Methods:</p>
                  <img src="../../dist/img/credit/visa.png" alt="Visa">
                  <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../../dist/img/credit/american-express.png" alt="American Express">
                  <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                    plugg
                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                  </p>-->
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <!--<p class="lead">Amount Due 2/22/2014</p>-->

                  <div class="table-responsive">
                    <table class="table">
                      <!--<tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>$250.30</td>
                      </tr>
                      <tr>
                        <th>Tax (9.3%)</th>
                        <td>$10.34</td>
                      </tr>
                      <tr>
                        <th>Shipping:</th>
                        <td>$5.80</td>
                      </tr>-->
                      <tr>
                        <th style="width: 50%">Total:</th>
                        <td>RM<?= $total?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <!--<a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>-->
                  <!--<button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                  </button>-->
                  <!--<button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>-->
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>