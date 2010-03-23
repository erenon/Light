<?php
/**
 * Page controller
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Controller
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Zend/Controller/Action.php';

class PageController extends Zend_Controller_Action
{
    public function showAction()
    {
        $request = $this->getRequest();
        $content = $request->getParam('content');
        $language = $request->getParam('language');

        $service = Light_Service_Abstract::getService('Page', 'Default');
        $page = $service->find($content, $language);

        $this->view->page = $page;
    }
}