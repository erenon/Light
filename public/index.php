<?php
/**
 * $LICENSE$
 *
 * Index page
 *
 * The only way in
 *
 * @category   Light
 * @package    Light_Application
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

// Define path to application directory
defined('APPLICATION_PATH')
    || define(
        'APPLICATION_PATH',
        realpath(dirname(__FILE__) . '/../application')
    );

// Define application environment
if ( ! defined('APPLICATION_ENV')) {
    if (getenv('APPLICATION_ENV')) {
        define('APPLICATION_ENV', getenv('APPLICATION_ENV'));
    } else {
        define('APPLICATION_ENV', 'production');
    }
}

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/config.php'
);
$application->bootstrap()
            ->run();