<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    /* 上传文件 */
    'upload' => [
        'url'  => Yii::getAlias('@storageUrl/image/'),
        //'path' => Yii::getAlias('@base/web/storage/image/'), // 服务器解析到/web/目录时，上传到这里
        'path' => Yii::getAlias('@storage/web/image/'),
    ],

    /* UEditor编辑器配置 */
    'ueditorConfig' => [
        /* 图片上传配置 */
        'imageRoot'            => Yii::getAlias("@storage/web"), //图片path前缀
        //'imageRoot'            => Yii::getAlias("@base/web/storage"), //图片path前缀，服务器解析到/web/目录时，上传到这里
        'imageUrlPrefix'       => Yii::getAlias('@storageUrl'), //图片url前缀
        'imagePathFormat'      => '/image/{yyyy}{mm}/editor{time}{rand:6}',

        /* 文件上传配置 */
        'fileRoot'             => Yii::getAlias("@storage/web"), //文件path前缀
        //'fileRoot'             => Yii::getAlias("@base/web/storage"), //文件path前缀，服务器解析到/web/目录时，上传到这里
        'fileUrlPrefix'        => Yii::getAlias('@storageUrl'), //文件url前缀
        'filePathFormat'       => '/file/{yyyy}{mm}/editor{rand:4}_{filename}',

        /* 上传视频配置 */
        'videoRoot'            => Yii::getAlias("@storage/web"),
        //'videoRoot'            => Yii::getAlias("@base/web/storage"), // 服务器解析到/web/目录时，上传到这里
        "videoUrlPrefix"       => Yii::getAlias('@storageUrl'),
        'videoPathFormat'      => '/video/{yyyy}{mm}/editor{time}{rand:6}',

        /* 涂鸦图片上传配置项 */
        'scrawlRoot'           => Yii::getAlias("@storage/web"),
        //'scrawlRoot'           => Yii::getAlias("@base/web/storage"), // 服务器解析到/web/目录时，上传到这里
        "scrawlUrlPrefix"      => Yii::getAlias('@storageUrl'),
        'scrawlPathFormat'     => '/image/{yyyy}{mm}/editor{time}{rand:6}',
    ],
];
