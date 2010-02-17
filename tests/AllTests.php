<?php
/**
 * Root of all tests
 *
 * Run phpunit AllTests.php in order to run all the tests
 *
 *
 * @category   Light
 * @package    Light_Test
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

require_once 'application/AllTests.php';

/**
 * This class is the root of all tests.
 *
 * @category Light
 * @package Light_Test
 * @license New BSD License
 * @author erenon
 *
 */
class AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Light');
        $suite->addTest(Application_AllTests::suite());

        return $suite;
    }
}