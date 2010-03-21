<?php
/**
 * Default_PageController test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Page
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once '../application/modules/default/controllers/Page.php';
require_once 'Light/Service/Abstract.php';

/**
 * Default_PageController test
 *
 * @category Light
 * @package Light_Test
 * @subpackage Page
 * @license New BSD License
 * @author erenon
 *
 * @group Light_Page
 *
 */
class Application_Modules_Default_Controllers_PageTest
    extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function testShowCallsServiceFind()
    {
        $content = 'foo';
        $language = 'bar';

        $service = $this->getMock('Default_Page_Service', array('find'));
        $service->expects($this->once())
                ->method('find')
                ->with($content, $language);

        Light_Service_Abstract::setService($service, 'Page', 'Default');

        $this->dispatch('/default/page/show/foo/bar');

    }
}