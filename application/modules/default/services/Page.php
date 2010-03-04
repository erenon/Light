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
    /**
     * Backend type: File
     *
     * @var string
     */
    const BACKEND_FILE = 'File';

    /**
     * Backend type: Database
     *
     * @var string
     */
    const BACKEND_DATABASE = 'Database';

    private $_page;
    private $_mapper;
    private $_backend;

    /**
     * Sets the given page to use.
     *
     * This method originally has been introduced
     * because testing reasons.
     *
     * @param Default_Model_Page $page
     * @return $this
     */
    public function setPage(Default_Model_Page $page)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * Returns a Page to use.
     *
     * Returns a brand new Default_Model_Page,
     * if no page given previously to setPage()
     *
     * @return Default_Model_Page
     */
    public function getPage()
    {
        if ($this->_page instanceOf Default_Model_Page) {
            return $this->_page;
        } else {
            return new Default_Model_Page();
        }
    }

    /**
     * Sets the mapper to use
     *
     * Typehint is disabled becouse of testing problems.
     * Mock object can't implement interfaces.
     *
     * @param Default_Model_PageMapperInterface $mapper
     * @return $this
     */
    public function setMapper(/*Default_Model_PageMapperInterface*/ $mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    /**
     * Returns the used mapper. If mapper is not yet inited,
     * sets default (Default_Model_PageFileMapper).
     *
     * @return Default_Model_PageMapperInterface
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_PageFileMapper());
        }
        return $this->_mapper;
    }

    /**
     * Sets the backend to use.
     *
     * Available backend types are Default_Service_Page::BACKEND_FILE
     * and Default_Service_Page::BACKEND_DATABASE
     *
     * @param string $backend
     * @return $this
     * @throws Exception If the backend type was wrong
     * @uses Default_Service_Page::BACKEND_FILE
     * @uses Default_Service_Page::BACKEND_DATABASE
     */
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

    /**
     * Returns the used backend type
     *
     * @return string
     */
    public function getBackend()
    {
        return $this->_backend;
    }

    /**
     * Finds a Page based on contentAlias and lang
     *
     * @param string $contentAlias Alias of the searched page
     * @param string $lang Language
     * @return Default_Model_Page
     */
    public function find($contentAlias, $lang)
    {
        return $this->getMapper()->find($contentAlias, $lang, $this->getPage());
    }
}