<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Library
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Light/Service/Abstract.php';

/**
 * Library Service Abstract test suite
 *
 * @category Light
 * @package Light_Test
 * @subpackage Library
 * @license New BSD License
 * @author erenon
 *
 * @group Light_Library
 *
 */
class Library_Light_Service_AbstractTest
      extends PHPUnit_Framework_TestCase
{
    public function testSetGetService()
    {
        $mock = $this->getMock('ServiceFoo');
        $serviceName = 'Foo';
        $module = 'Bar';

        Light_Service_Abstract::setService($mock, $serviceName, $module);

        $this->assertEquals(
            $mock,
            Light_Service_Abstract::getService($serviceName, $module)
        );
    }
}