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

class Default_PageController extends Zend_Controller_Action
{
    public function showAction()
    {
        $service = Light_Service_Abstract::getService('Page', 'Default');
    }
}