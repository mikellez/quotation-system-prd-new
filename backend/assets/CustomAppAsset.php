<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class CustomAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/custom.css',
    ];
    public $js = [
        'js/main.js',
    ];
    public $depends = [
        'backend\assets\AdminLTEAsset',
    ];
}
