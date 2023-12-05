<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\CustomAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use backend\components\Menu;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

CustomAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!--<li class="nav-item d-none d-sm-inline-block">
        <a href="<?= Yii::$app->urlManager->createUrl('site/logout')?>" class="nav-link">Logout</a>
      </li>-->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Messages Dropdown Menu -->
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= Yii::$app->urlManager->createUrl('site/logout')?>" class="nav-link">Logout</a>
      </li>
      <!-- Notifications Dropdown Menu -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="https://via.placeholder.com/150" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?= Yii::$app->name?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="https://via.placeholder.com/50" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= Yii::$app->user->identity->username?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!--<div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>-->

      <!-- Sidebar Menu -->
        <nav class="mt-2">
        <?php
        Menu::begin();
        /*$menuItems = [
            [
                'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-tachometer-alt']).Html::tag('p','Dashboard'),
                'options'=>[
                    'class'=>'nav-item',
                ],
                'url'=>['site/index'],
                //'template'=>'<a href="{url}" class="nav-link {active}">{label}</a>',
                'active'=>Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index',
                /*'items'=> [
                    [
                        'label' => Html::tag('i','', [ 'class'=>'far fa-circle nav-icon']).Html::tag('p','Dashboard v1'),
                        'url'=>['/site/index'],
                        'template'=>'<a href="{url}" class="nav-link {active}">{label}</a>',
                        'active'=>true
                    ],
                    [
                        'label' => Html::tag('i','', [ 'class'=>'far fa-circle nav-icon']).Html::tag('p','Dashboard v2'),
                        'url'=>'#',
                    ],
                    [
                        'label' => Html::tag('i','', [ 'class'=>'far fa-circle nav-icon']).Html::tag('p','Dashboard v3'),
                        'url'=>'#',
                    ],
                ]
            ],
        ];*/
        if(Yii::$app->user->can('view-dashboard')) {
          $menuItems = [
              [
                  'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-tachometer-alt']).Html::tag('p','Dashboard'),
                  'options'=>[
                      'class'=>'nav-item',
                  ],
                  'url'=>['site/index'],
                  'active'=>Yii::$app->controller->id == 'site',
              ],
          ];
        }

        if(Yii::$app->user->can('create-product')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-list']).Html::tag('p','Product'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['product/index'],
            'active'=>Yii::$app->controller->id == 'product',
          ];
        }

        if(Yii::$app->user->can('create-brand')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-tags']).Html::tag('p','Brand'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['brand/index'],
            'active'=>Yii::$app->controller->id == 'brand',
          ];
        }
        
        if(Yii::$app->user->can('create-brand')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-sitemap']).Html::tag('p','Category'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['category/index'],
            'active'=>Yii::$app->controller->id == 'category',
          ];
        }

        if(Yii::$app->user->can('create-user')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-user']).Html::tag('p','User'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['user/index'],
            'active'=>Yii::$app->controller->id == 'user',
          ];
        }

        $menuItems[] = [
          'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-users']).Html::tag('p','Client'),
          'options'=>[
              'class'=>'nav-item',
          ],
          'url'=>['client/index'],
          'active'=>Yii::$app->controller->id == 'client',
        ];

        $menuItems[] = [
          'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-building']).Html::tag('p','Company Group'),
          'options'=>[
              'class'=>'nav-item',
          ],
          'url'=>['company/index'],
          'active'=>Yii::$app->controller->id == 'company',
        ];

        $auth_assignment = \common\models\AuthAssignment::findOne(["user_id"=>Yii::$app->user->id]);

        if($auth_assignment->item_name == "officer") {
          $countQuotation = \common\models\Quotation::find()->where(['status'=>\common\models\Quotation::STATUS_PENDING,'created_by'=>Yii::$app->user->id])->count();
        } else {
          $countQuotation = \common\models\Quotation::find()->where(['status'=>\common\models\Quotation::STATUS_PENDING])->count();
        }
        $badgeQuotation = '';

        if($countQuotation > 0) {
          $badgeQuotation = Html::tag('span', $countQuotation, ['class' => 'badge badge-danger']);
        }

        if(Yii::$app->user->can('create-quotation')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-file']).Html::tag('p','Quotation').'&nbsp;'.$badgeQuotation,
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['quotation/index'],
            'active'=>Yii::$app->controller->id == 'quotation',
          ];
        }

        if($auth_assignment->item_name == "officer") {
          $countProjectQuotation = \common\models\Quotation::find()->where(['status'=>\common\models\Quotation::STATUS_PENDING, 'master'=>1, 'created_by'=>Yii::$app->user->id])->count();
        } else {
          $countProjectQuotation = \common\models\Quotation::find()->where(['status'=>\common\models\Quotation::STATUS_PENDING, 'master'=>1])->count();
        }
        $badgeProjectQuotation = '';

        if($countProjectQuotation > 0) {
          $badgeProjectQuotation = Html::tag('span', $countProjectQuotation, ['class' => 'badge badge-danger']);
        }

        if(Yii::$app->user->can('create-quotation')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-copy']).Html::tag('p','Project Quotation'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['project-quotation/index'],
            'active'=>Yii::$app->controller->id == 'project-quotation',
          ];
        }

        $menuItems[] = [
          'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-copy']).Html::tag('p','Point Collection'),
          'options'=>[
              'class'=>'nav-item',
          ],
          'url'=>['point-collection/index'],
          'active'=>Yii::$app->controller->id == 'point-collection',
        ];

        if(Yii::$app->user->can('create-quotation')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-copy']).Html::tag('p','Point Transfer'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['point-transfer/index'],
            'active'=>Yii::$app->controller->id == 'point-transfer',
          ];
        }

        if(Yii::$app->user->can('view-point-summary')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-copy']).Html::tag('p','Point Summary'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['point-summary/user'],
            'active'=>Yii::$app->controller->id == 'point-summary',
          ];
        }

        /*if(Yii::$app->user->can('create-quotation')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-copy']).Html::tag('p','Point Redemption'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['point-redemption/index'],
            'active'=>Yii::$app->controller->id == 'point-redemption',
          ];
        }*/

        if(Yii::$app->user->can('view-profit-analysis')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-copy']).Html::tag('p','Profit Analysis'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['profit-analysis/index'],
            'active'=>Yii::$app->controller->id == 'profit-analysis',
          ];
        }

        /*if(Yii::$app->user->can('create-sales-order')) {
          $menuItems[] = [
            'label' => Html::tag('i','', [ 'class'=>'nav-icon fas fa-cog']).Html::tag('p','Settings'),
            'options'=>[
                'class'=>'nav-item',
            ],
            'url'=>['settings/index'],
            'active'=>Yii::$app->controller->id == 'sales' && Yii::$app->controller->action->id == 'index',
          ];
        }*/

        echo Menu::widget([
            'encodeLabels'=>false,
            'options' => [
                'class' => 'nav nav-pills nav-sidebar flex-column',
                'data-widget'=>'treeview',
                'role'=>'menu',
                'data-accordion'=>'false'
            ],
            'linkTemplate'=>'<a class="nav-link {active}" href="{url}">{label}</a>',
            'submenuTemplate'=>'<ul class="nav nav-treeview">{items}</ul>',
            'itemOptions'=>[
                'class'=>'nav-item',
            ],
            'items' => $menuItems,
        ]);
        Menu::end();
        ?>
        </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <div class="content-wrapper" style="min-height: 169px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <!--<h1 class="m-0">Dashboard</h1>-->
              <?php echo Html::a('<i class="fa fa-arrow-left"></i> Back', Yii::$app->request->referrer ?: Yii::$app->homeUrl, ['class' => 'btn btn-default']);?>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <?= 
                Breadcrumbs::widget([
                    'homeLink' => [ 
                                    'label' => Yii::t('yii', 'Dashboard'),
                                    'url' => Yii::$app->homeUrl,
                              ],
                    'options'=>['class'=>'breadcrumb float-sm-right'],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'itemTemplate'=>"<li class='breadcrumb-item'>{link}</li>\n",
                    'activeItemTemplate'=>"<li class='breadcrumb-item active'>{link}</li>\n"
                ]) 
              ?>
              <!--<ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>-->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <section class="content">
        <div class="container-fluid">
          <?= $content?>
        </div>
      </section>
  </div>
</div>


<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; <?= date('Y')?> <?= Html::encode(Yii::$app->name) ?> .</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <!--<b>Version</b> 3.1.0-->
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
