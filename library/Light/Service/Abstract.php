<?php
/**
 * Abstract Service
 *
 * @category   Light
 * @package    Light_Library
 * @subpackage Service
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */


/**
 * Abstract Service class
 *
 * Provides common, non-abstract methods, as acl/cache lookup,
 * also provide static getService method
 *
 * @category Light
 * @package Light_Library
 * @subpackage Service
 * @license New BSD License
 * @author erenon
 *
 */
class Light_Service_Abstract
{
    private static $_services = array();

    public static function getService($serviceName, $module)
    {
        if ( ! isset(self::$_services[$module][$serviceName])) {
            $this->_includeClass($serviceName, $module);
            $className = $module . '_Service_' . $serviceName;
            self::setService(new $className(), $serviceName, $module);
        }

        return self::$_services[$module][$serviceName];
    }

    public static function setService($service, $serviceName, $module)
    {
        self::$_services[$module][$serviceName] = $service;
    }

    private static function _includeClass($serviceName, $module)
    {
        $fileName = $serviceName . ".php";

        require_once APPLICATION_PATH .
            DIRECTORY_SEPARATOR .
            'modules' .
            DIRECTORY_SEPARATOR .
            $module .
            DIRECTORY_SEPARATOR .
            'services' .
            DIRECTORY_SEPARATOR .
            $fileName;
    }
}