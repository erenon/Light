<?php
/**
 * Setup test environment
 *
 * @category   Light
 * @package    Light_Test
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

/**
 * Add application library to the include path.
 * This hardcoded path may change if application config is presented
 */

$lightRoot   = realpath(dirname(dirname(__FILE__)));
$libraryPath = $lightRoot . "/library/";

$includePath = array(
    $libraryPath,
    get_include_path()
    );
set_include_path(implode(PATH_SEPARATOR, $includePath));

/*
 * Setup APPLICATION_PATH
 */
define(
    'APPLICATION_PATH',
    $lightRoot . DIRECTORY_SEPARATOR . 'application'
);

/*
 * include zend test suites
 */
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

/*
 * Remove no more used variables
 */
unset($lightRoot, $libraryPath, $includePath);