<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    /* 源语言 */
    'sourceLanguage' => 'zh-CN',
    /* 目标语言 */
    'language' => 'zh-CN',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
