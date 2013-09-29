<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

#$pre_config = CMap::mergeArray(
#    require(dirname(__FILE__).'/local.php')
#);
$pre_config = require(dirname(__FILE__).'/local.php') ;
Yii::log(print_r($pre_config , true) , 'debug');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array_merge_recursive(array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Web Application',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.vendor.*',
    ),


    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                //'search/<search:.+>'=>'site/index',
                //'search'=>'site/index',
                //'download/<search:.+>'=>'site/index',
                //'download'=>'site/index',
                //'.*'=>'site/index',
            ),
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning , debug',
                ),
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'webmaster@example.com',
    ),
), $pre_config);
