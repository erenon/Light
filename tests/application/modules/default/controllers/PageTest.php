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

require_once '../application/modules/default/controllers/PageController.php';

require_once 'Light/Service/Abstract.php';

require_once 'Zend/Controller/Request/Abstract.php';
require_once 'Zend/Controller/Response/Abstract.php';
require_once 'Zend/View.php';

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
    extends PHPUnit_Framework_TestCase
{
    public function testShowCallsServiceFind()
    {
        $content = 'foo';
        $language = 'bar';

        $page = $this->getMock('Default_Model_Page');

        $service = $this->getMock('Default_Page_Service', array('find'));
        $service->expects($this->once())
                ->method('find')
                ->with($content, $language)
                ->will($this->returnValue($page));

        Light_Service_Abstract::setService($service, 'Page', 'Default');

        $request = $this->getMock('Zend_Controller_Request_Abstract', array('getParam'));
        $request->expects($this->any())
                ->method('getParam')
                ->will($this->returnCallback(array($this, 'getParam')));

        $response = $this->getMock('Zend_Controller_Response_Abstract');

        $controller = new PageController($request, $response);

        //$view = $this->getMock('Zend_View');
        $view = new Zend_View();
        $controller->view = $view;

        $controller->showAction();

        $this->assertEquals(
            $page,
            $view->page
        );

    }

    public function getParam($key)
    {
        $parameters = array(
            'content'  => 'foo',
            'language' => 'bar',
        );

        if ( isset($parameters[$key])) {
            return $parameters[$key];
        } else {
            return null;
        }
    }
}