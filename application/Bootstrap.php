<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Application
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Zend/Application/Bootstrap/Bootstrap.php';

/**
 * Bootstrap class, runs on every request
 *
 * @category Light
 * @package LIght_Application
 * @license New BSD License
 * @author erenon
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Enables resource autoload to the default module
     *
     * @todo should refactor into config file
     */
    protected function _initModuleAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath'  => APPLICATION_PATH . '/modules/default',
        ));

        $this->getContainer()->{'defaultAutoloader'} = $autoloader;
    }
}