<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/bootbox.min.js',
        'js/proc.js',
        'js/jquery-deserialize-master/jquery.deserialize.js',
        'js/jquery.maskedinput.min.js',
            //  'js/test.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
