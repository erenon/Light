<?php
/**
 * Page File Mapper
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Light_Page_Mapper
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */


/**
 * Page File Mapper
 *
 * Maps files into models
 *
 * @category Light
 * @package Light_Page
 * @subpackage Light_Page_Mapper
 * @license New BSD License
 * @author erenon
 *
 */
class Default_Model_PageFileMapper
{
    const TITLE_CONTENT_SEPARATOR = "\n";

    protected $_directoryRoot;

    public function setDirectoryRoot($root)
    {
        $this->_directoryRoot = $root;
        return $this;
    }

    public function getDirectoryRoot()
    {
        return $this->_directoryRoot;
    }

    public function find($contentAlias, $lang, Default_Model_Page $page)
    {
        $file = file_get_contents($this->getDirectoryRoot() .
                                  DIRECTORY_SEPARATOR .
                                  $lang .
                                  DIRECTORY_SEPARATOR .
                                  $contentAlias);

        $fileTitleContent = explode(self::TITLE_CONTENT_SEPARATOR,
                                    $file, 2);

        $title = $fileTitleContent[0];
        $content = $fileTitleContent[1];

        $page->setTitle($title);
        $page->setContent($content);
        $page->setAlias($contentAlias);
    }
}