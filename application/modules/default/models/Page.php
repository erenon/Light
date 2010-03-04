<?php
/**
 * Page Model
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Model
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

/**
 * Page Model
 *
 * Holds static informations, has id, title, alias, content.
 *
 * @category Light
 * @package Light_Page
 * @subpackage Model
 * @license New BSD License
 * @author erenon
 * @todo improve doc
 *
 */
class Default_Model_Page
{
    private $_id;
    private $_title;
    private $_alias;
    private $_content;

    public function setId($id)
    {
      $this->_id = (int) $id;
      return $this;
    }

    public function getId()
    {
      return $this->_id;
    }

    public function setTitle($title)
    {
      $this->_title = (string) $title;
      return $this;
    }

    public function getTitle()
    {
      return $this->_title;
    }

    public function setAlias($alias)
    {
      $this->_alias = (string) $alias;
      return $this;
    }

    public function getAlias()
    {
      return $this->_alias;
    }

    public function setContent($content)
    {
      $this->_content = (string) $content;
      return $this;
    }

    public function getContent()
    {
      return $this->_content;
    }
}