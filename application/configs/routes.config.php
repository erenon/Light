<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Application
 * @subpackage Config
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

return array(
    'page' => array(
        'type'  => 'Zend_Controller_Router_Route',
        'route' => 'page/show/:language/:content',
        'defaults' => array(
            'module'     => 'default',
            'controller' => 'page',
            'action'     => 'show'
        )
    ),
);