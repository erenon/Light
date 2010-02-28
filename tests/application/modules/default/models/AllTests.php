<?php
/**
 * Root of Application_Modules_Default_Models test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Light_Test_Models
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

require_once 'application/modules/default/models/PageTest.php';
require_once 'application/modules/default/models/PageFileMapperTest.php';

/**
 * Application_Modules_Default_Models tests
 *
 * @category Light
 * @package Light_Test
 * @subpackage Light_Test_Models
 * @license New BSD License
 * @author erenon
 *
 */
class Application_Modules_Default_ModelsAllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Light_Application_Modules_Default_Models');
        //$suite->addTestSuite('Application_Modules_Default_Models_PageTest');
        $suite->addTestSuite('Application_Modules_Default_Models_PageFileMapperTest');

        return $suite;
    }
}