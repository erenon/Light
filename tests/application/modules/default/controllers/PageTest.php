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

require_once 'Light/Exception.php';
require_once 'Light/Exception/NotFound.php';

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
    /**
     * Tests if controller calls page service with proper arguments
     * and sets up view->page field
     */
    public function testShowCallsServiceFind()
    {
        $content = $this->getRequestParam('content');
        $language = $this->getRequestParam('language');

        $page = $this->getMock('Default_Model_Page');

        $service = $this->getMock('Default_Page_Service', array('find'));
        $service->expects($this->once())
                ->method('find')
                ->with($content, $language)
                ->will($this->returnValue($page));

        Light_Service_Abstract::setService($service, 'Page', 'Default');

        $request = $this->getMock(
            'Zend_Controller_Request_Abstract',
            array('getParam')
        );

        $request->expects($this->any())
                ->method('getParam')
                ->will($this->returnCallback(array($this, 'getRequestParam')));

        $response = $this->getMock('Zend_Controller_Response_Abstract');

        $controller = new Default_PageController($request, $response);

        //$view = $this->getMock('Zend_View');
        //mocking zend_view doesn't work becouse of a mocked __set()
        $view = new Zend_View();
        $controller->view = $view;

        $controller->showAction();

        $this->assertEquals(
            $page,
            $view->page
        );

    }

    /**
     * Provides test request parameters, content and language
     *
     * @param string $key request parameter name
     * @return null|<string>request parameter value
     */
    public function getRequestParam($key)
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

    /**
     * Test not found
     *
     * @expectedException Light_Exception_NotFound
     */
    public function testShowThrowsNotFound()
    {
        $content = $this->getRequestParam('content');
        $language = $this->getRequestParam('language');

        $service = $this->getMock('Default_Page_Service', array('find'));
        $service->expects($this->once())
                ->method('find')
                ->will($this->throwException(new Light_Exception_NotFound()));

        Light_Service_Abstract::setService($service, 'Page', 'Default');

        $request = $this->getMock(
            'Zend_Controller_Request_Abstract',
            array('getParam')
        );

        $request->expects($this->any())
                ->method('getParam')
                ->will($this->returnCallback(array($this, 'getRequestParam')));

        $response = $this->getMock('Zend_Controller_Response_Abstract');

        $controller = new Default_PageController($request, $response);

        $controller->showAction();
    }

    /**
     * Test internal error
     *
     * @expectedException Light_Exception
     */
    public function testShowThrowsInternalError()
    {
        $content = $this->getRequestParam('content');
        $language = $this->getRequestParam('language');

        $service = $this->getMock('Default_Page_Service', array('find'));
        $service->expects($this->once())
                ->method('find')
                ->will($this->throwException(new Light_Exception()));

        Light_Service_Abstract::setService($service, 'Page', 'Default');

        $request = $this->getMock(
            'Zend_Controller_Request_Abstract',
            array('getParam')
        );

        $request->expects($this->any())
                ->method('getParam')
                ->will($this->returnCallback(array($this, 'getRequestParam')));

        $response = $this->getMock('Zend_Controller_Response_Abstract');

        $controller = new Default_PageController($request, $response);

        $controller->showAction();
    }
}