<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Library
 * @subpackage Test
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

require_once 'Zend/Application.php';

/**
 * Wrapper class to implement some common methods
 *
 * @category   Light
 * @package    Light_Library
 * @subpackage Test
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */
abstract class Light_Test_ControllerTestCase
    extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->application = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/configs/config.php'
        );

        $this->bootstrap = array(
            $this->application,
            'bootstrap'
        );

        parent::setUp();
    }
}