<?php
/**
 * Abstract model class
 *
 * @category   Light
 * @package    Light_Library
 * @subpackage Model
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */


/**
 * Abstract model class
 *
 * @category Light
 * @package Light_Library
 * @subpackage Model
 * @license New BSD License
 * @author erenon
 *
 */
abstract class Light_Model_Abstract
{
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}