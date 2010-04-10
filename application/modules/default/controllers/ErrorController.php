<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Error
 * @subpackage Controller
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

class Default_ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        var_dump($errors->exception);

        $this->_helper->viewRenderer->setNoRender(true);
    }
}