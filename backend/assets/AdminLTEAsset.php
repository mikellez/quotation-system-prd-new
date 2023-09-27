<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AdminLTEAsset extends AssetBundle
{
    //public $basePath = '';
    public $sourcePath = '@vendor/almasaeed2010/adminlte';
    public $css = [
        'plugins/fontawesome-free/css/all.min.css',
        //'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        //'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'plugins/jqvmap/jqvmap.min.css',
        'plugins/bs-stepper/css/bs-stepper.min.css',
        'dist/css/adminlte.min.css',
        'plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/summernote/summernote-bs4.min.css',
    ];
    public $js = [
        //'plugins/jquery/jquery.min.js',
        //'plugins/jquery-ui/jquery-ui.min.js',
        //'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'plugins/chart.js/Chart.min.js',
        'plugins/sparklines/sparkline.js',
        'plugins/jqvmap/jquery.vmap.min.js',
        'plugins/jqvmap/maps/jquery.vmap.usa.js',
        'plugins/jquery-knob/jquery.knob.min.js',
        'plugins/moment/moment.min.js',
        'plugins/daterangepicker/daterangepicker.js',
        //'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
        'plugins/summernote/summernote-bs4.min.js',
        'plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
        'plugins/bs-stepper/js/bs-stepper.min.js',
        'dist/js/adminlte.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
        //'yii\web\YiiAsset',
        //'yii\bootstrap4\BootstrapAsset',
    ];
}
