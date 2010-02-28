<?php
/**
 * Page Service
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Light_Page_Service
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */


/**
 * Page Service
 *
 * Should provide text contents through solid interface,
 * based on content identifier and language.
 *
 * @category Light
 * @package Light_Page
 * @subpackage Light_Page_Service
 * @license New BSD License
 * @author erenon
 *
 * @todo Improve doc
 *
 */
class Default_Service_Page
{
    const BACKEND_FILE = 'File';
    const BACKEND_DATABASE = 'Database';

    private $_page;
    private $_mapper;
    private $_backend;

    public function setPage(Default_Model_Page $page)
    {
        $this->_page = $page;
        return $this;
    }

    public function getPage()
    {
        if ($this->_page instanceOf Default_Model_Page) {
            return $this->_page;
        } else {
            return new Default_Model_Page();
        }
    }

    public function setMapper(/*Default_Model_PageMapperInterface*/ $mapper)
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

    public function getBackend()
    {
        return $this->_backend;
    }

    public function find($contentAlias, $lang)
    {
        return $this->getMapper()->find($contentAlias, $lang, $this->getPage());
    }
}