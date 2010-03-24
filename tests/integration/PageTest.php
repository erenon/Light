<?php
/**
 * Default_PageController test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Integration
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Light/Service/Abstract.php';

require_once 'Light/Test/ControllerTestCase.php';

/**
 * Default_PageController test
 *
 * @category Light
 * @package Light_Test
 * @subpackage Integration
 * @license New BSD License
 * @author erenon
 *
 * @group Light_Page
 * @group Light_Integration
 *
 * @todo remove service expectations
 * @todo check for output
 *
 */
class Integration_PageTest extends Light_Test_ControllerTestCase
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

        $this->request->setQuery(
            array(
                'content' => $content,
                'language' => $language,
            )
        );

        $this->dispatch('/page/show');

        $this->assertModule('default');
        $this->assertController('page');
        $this->assertAction('show');
    }
}