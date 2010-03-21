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

//require_once '../application/modules/default/controllers/PageController.php';
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
    public function setUp()
    {
        parent::setUp();
        $this->frontController->setParam('noErrorHandler', true);
        $this->frontController->addModuleDirectory(APPLICATION_PATH . '/modules');
        $this->frontController->setModuleControllerDirectoryName('controllers');

        $this->frontController->setDefaultModule('default');
        $this->frontController->setDefaultAction('index');
        $this->frontController->setDefaultControllerName('index');

        $this->frontController->setControllerDirectory(APPLICATION_PATH . '/modules/default/controllers/', 'default');
    }

    public function testShowCallsServiceFind()
    {
        $content = 'foo';
        $language = 'bar';

        $service = $this->getMock('Default_Page_Service', array('find'));
        $service->expects($this->once())
                ->method('find')
                ->with($content, $language);

        Light_Service_Abstract::setService($service, 'Page', 'Default');

        $this->request->setQuery(array(
            'content' => $content,
            'language' => $language,
        ));

        $this->dispatch('/page/show');

        $this->assertModule('default');
        $this->assertController('page');
        $this->assertAction('show');

    }
}