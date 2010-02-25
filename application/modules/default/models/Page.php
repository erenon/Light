<?php
/**
 * Page Model
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Light_Page_Model
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
 * @subpackage Light_Page_Model
 * @license New BSD License
 * @author erenon
 *
 */
class Default_Model_Page
{
    const BACKEND_FILE = 'File';
    const BACKEND_DATABASE = 'Database';

    private $_id;
    private $_title;
    private $_alias;
    private $_content;

    private $_mapper;
    private $_backend;

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

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_PageFileMapper());
        }
        return $this->_mapper;
    }

    public function getBackend()
    {
        return $this->_backend;
    }

    public function setBackend($backend)
    {
        switch ($backend) {
            case self::BACKEND_FILE:
                $this->_backend = self::BACKEND_FILE;
                $this->setMapper(new Default_Model_PageFileMapper());
                break;

            case self::BACKEND_DATABASE:
                $this->_backend = self::BACKEND_DATABASE;
                $this->setMapper(new Default_Model_PageDbMapper());
                break;

            default:
                throw new Exception('Wrong backend type given');
                break;
        }

        return $this;
    }

    public function find($contentAlias, $lang)
    {
        $this->getMapper()->find($contentAlias, $lang, $this);
        return $this;
    }
}