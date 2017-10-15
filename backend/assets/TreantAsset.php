<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/15
 * Time: 上午1:38
 */

namespace backend\assets;


use yii\web\AssetBundle;

class TreantAsset extends AssetBundle
{
    public $sourcePath = '@common/metronic';

    /* 全局CSS文件 */
    public $css = [
        'treant/Treant.css',
    ];

    /* 全局JS文件 */
    public $js = [
        'treant/Treant.js',
        'treant/vendor/raphael.js',
    ];

    /* 依赖关系 */
    public $depends = [
        'backend\assets\CoreAsset',
    ];
}