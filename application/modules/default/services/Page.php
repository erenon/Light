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
    private $_page;

    public function setPage(Default_Model_Page $page)
    {
        $this->_page = $page;
        return $this;
    }

    public function getPage()
    {
        if (null === $this->_page) {
            $this->setPage(new Default_Model_Page());
        }

        return $this->_page;
    }

    public function find($contentAlias, $lang)
    {
        return $this->_page->find($contentAlias, $lang);
    }
}