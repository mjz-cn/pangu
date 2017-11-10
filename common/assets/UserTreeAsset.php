<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/15
 * Time: 下午10:33
 */

namespace common\assets;


use yii\web\AssetBundle;

class UserTreeAsset extends AssetBundle
{
    public $sourcePath = '@common/metronic';

    /* 全局CSS文件 */
    public $css = [
        'other/css/user-tree.css',
    ];

    /* 全局JS文件 */
    public $js = [
        'other/js/user-tree.js',
    ];

    /* 依赖关系 */
    public $depends = [
        'common\assets\TreantAsset',
    ];
}