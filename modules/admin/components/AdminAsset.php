<?php

namespace app\modules\admin\components;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by admin.
 */
class AdminAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/assets';
    public $css = [
        'css/theme.css',
        'css/typicons.min.css',
        'css/style.css',
    ];
    public $js = [
        'js/jquery.liTranslit.js',
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
