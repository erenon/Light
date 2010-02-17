<?php
/**
 * Root of application tests
 *
 * @category   Light
 * @package    Light_Test
 * @license    BSD License
 * @version    $Id$
 * @author erenon
 */

require_once 'application/modules/AllTests.php';

/**
 * Application tests
 *
 * @category Light
 * @package Light_Test
 * @license New BSD License
 * @author erenon
 *
 */
class Application_AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Light_Application');
        $suite->addTest(Application_Modules_AllTests::suite());

        return $suite;
    }
}