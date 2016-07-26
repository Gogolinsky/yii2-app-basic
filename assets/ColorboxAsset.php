<?php

namespace app\assets;

use yii\web\AssetBundle;

class ColorboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/colorbox';
    public $css = [
        'example2/colorbox.css',
    ];
    public $js = [
        'jquery.colorbox-min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
