<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/15
 * Time: 下午10:33
 */

namespace backend\assets;


use yii\web\AssetBundle;

class UserRelationGraphAsset extends AssetBundle
{
    public $sourcePath = '@common/metronic';

    /* 全局CSS文件 */
    public $css = [
        'other/css/relation-graph.css',
    ];

    /* 全局JS文件 */
    public $js = [
        'other/js/relation-graph.js',
    ];

    /* 依赖关系 */
    public $depends = [
        'backend\assets\TreantAsset',
    ];
}