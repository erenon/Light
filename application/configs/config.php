<?php
/**
 * Main config
 *
 * @category   Light
 * @package    Light_Application
 * @subpackage Config
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

return array_merge_recursive(
    array(
        'resources'   => array(
            'frontController' => array(
                'moduleDirectory' => APPLICATION_PATH . '/modules',
                'moduleControllerDirectoryName' => '/controllers',
                'defaultModule' => 'default',
                'defaultAction' => 'index',
                'defaultControllerName' => 'index',
                'prefixDefaultModule' => true,

                'throwerrors' => false,
                'env' => APPLICATION_ENV
            ),
        ),
        'includePaths'    => array(
            'library' => APPLICATION_PATH . '/../library/'
        ),
        'bootstrap'    => array(
            'path' => APPLICATION_PATH . '/Bootstrap.php',
            'class' => 'Bootstrap'
        ),
        'phpSettings'    => array(
            'display_startup_errors' => 0,
            'display_errors' => 0
        ),
        'autoloaderNamespaces' => array(
        	'Light_'
        )
    ),
    include dirname(__FILE__) . '/' . APPLICATION_ENV . '.config.php'
);