<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午11:54
 */

class BootstrapDialogAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap3-dialog';

    /* 全局CSS文件 */
    public $css = [
        'dist/css/bootstrap-dialog.min.css',
    ];

    /* 全局JS文件 */
    public $js = [
        'dist/js/bootstrap-dialog.min.js',
    ];

    /* 依赖关系 */
    public $depends = [
        'common\assets\CommonCoreAsset',
    ];
}