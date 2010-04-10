<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Service
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
 * @subpackage Service
 * @license New BSD License
 * @author erenon
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
    private $_form;

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
     * @throws Light_Exception_InvalidParameter If the backend type was wrong
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
                require_once 'Light/Exception/InvalidParameter.php';
                throw new Light_Exception_InvalidParameter(
                    'Wrong backend type given'
                );
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
            return $this->getMapper()->find(
                $contentAlias,
                $lang,
                $this->getPage()
            );
    }

    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }

    public function getForm()
    {
        if (null === $this->_form) {
            $this->setForm(new Default_Form_Page());
        }
        return $this->_form;
    }

    /**
     * Creates and saves a page model based on the given request.
     *
     * @param array $request The request contains page fields
     * @return bool Returns true if the saving was successful, false otherwise
     */
    public function save(array $request)
    {
        $form = $this->getForm();

        if ($form->isValid($request)) {
            $values = $form->getValues();

            $page = $this->getPage();
            $page->setOptions($values);

            $alias = $page->getAlias();
            if (empty($alias)) {
                $page->setAliasFromTitle();
            }

            $this->getMapper()->save($page);

            return true;
        }

        return false;
    }
}