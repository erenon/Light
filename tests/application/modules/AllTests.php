<?php
/**
 * Root of Application_Modules test
 *
 * @category   Light
 * @package    Light_Test
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

require_once 'application/modules/default/AllTests.php';

/**
 * Application_Module tests
 *
 * @category Light
 * @package Light_Test
 * @license New BSD License
 * @author erenon
 *
 */
class Application_Modules_AllTests
{
    /**
     * Regular suite
     *
     * Includes all tests
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Light_Application_Modules');
        $suite->addTest(Application_Modules_Default_AllTests::suite());

        return $suite;
    }
}