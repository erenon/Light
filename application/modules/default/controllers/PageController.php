<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Controller
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Zend/Controller/Action.php';

require_once 'Light/Service/Abstract.php';

/**
 * Page controller
 *
 * Provides CRUD on simple Page models
 *
 * @category Light
 * @package Light_Page
 * @subpackage Controller
 * @license New BSD License
 * @author erenon
 *
 */
class Default_PageController extends Zend_Controller_Action
{
    /**
     * Displays static pages
     *
     * Interacts with the Page service via it's find method,
     * gets the requested page by content and language,
     * sets up the returned page to view->page
     *
     * @throws Light_Exception_NotFound If the requested page doesn't exist
     * @throws Light_Exception on internal error
     */
    public function showAction()
    {
        $request = $this->getRequest();
        $content = $request->getParam('content');
        $language = $request->getParam('language');

        $service = Light_Service_Abstract::getService('Page', 'Default');

        try
        {
            $page = $service->find($content, $language);
        } catch (Light_Exception_NotFound $notFound) {
            throw new Light_Exception_NotFound(
                'Requested page doesn\'t exists'
            );
        } catch (Light_Exception_InvalidParameter $invalid) {
            throw new Light_Exception_NotFound(
                'Requested page doesn\'t exists'
            );
            //it could be a bad request as well
        } catch (Light_Exception $exception) {
            throw new Light_Exception('Internal error');
        }

        $this->view->page = $page;
    }
}