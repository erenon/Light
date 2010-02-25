<?php
/**
 * Root of Application_Modules_Default_Services test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Light_Test_Page
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

require_once 'application/modules/default/services/PageTest.php';

/**
 * Application_Module_Default_Services tests
 *
 * @category Light
 * @package Light_Test
 * @license New BSD License
 * @author erenon
 *
 */
class Application_Modules_Default_ServicesAllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Light_Application_Modules_Default_Services');
        $suite->addTestSuite('Application_Modules_Default_Services_PageTest');

        return $suite;
    }
}