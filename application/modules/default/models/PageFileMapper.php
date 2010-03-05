<?php
/**
 * Page File Mapper
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Mapper
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
 * @subpackage Mapper
 * @license New BSD License
 * @author erenon
 *
 */
class Default_Model_PageFileMapper
{
    /**
     * The separator between title and content in the content file
     * @var string
     */
    const TITLE_CONTENT_SEPARATOR = "\n";

    /**
     * Directory root of content files
     *
     * This directory contains directories of supported languages.
     * These directories contain the content files.
     *
     * @var string
     */
    protected $_directoryRoot;

    /**
     * Sets the directory root
     *
     * @param string $root
     * @return $this
     */
    public function setDirectoryRoot($root)
    {
        $this->_directoryRoot = $root;
        return $this;
    }

    /**
     *
     * @return string Directory root
     */
    public function getDirectoryRoot()
    {
        return $this->_directoryRoot;
    }

    /**
     * Filters malicious filenames to prevent directory traversals, R/LFI, etc.
     *
     * @param string $alias
     * @return string filtered alias
     */
    public function filterContentAlias($alias)
    {
        $info = pathinfo($alias);

        $file = preg_replace('/[^a-zA-Z0-9_\-]*/', '', $info['filename']);

        $allowedExts = array('txt', 'html');

        if (    isset($info['extension'])
            AND in_array($info['extension'], $allowedExts)
        ) {
            $file = $file . '.' . $info['extension'];
        }

        return $file;
    }

    /**
     * Reads content from the filesystem
     * based on the given contentAlias and language.
     *
     * @param string $contentAlias
     * @param string $lang
     * @param Default_Model_Page $page
     * @return Default_Model_Page
     * @throws Exception
     * @uses TITLE_CONTENT_SEPARATOR
     */
    public function find($contentAlias, $lang, Default_Model_Page $page)
    {
        $filePath = $this->getDirectoryRoot()
                  . DIRECTORY_SEPARATOR
                  . $lang
                  . DIRECTORY_SEPARATOR
                  . $contentAlias;

        if ( ! is_readable($filePath)) {
            throw new Exception('File not found or not readable');
        }

        $file = file_get_contents($filePath);

        $fileTitleContent = explode(
            self::TITLE_CONTENT_SEPARATOR,
            $file,
            2
        );

        if (isset($fileTitleContent[1])) {
            //content has title
            $title = $fileTitleContent[0];
            $page->setTitle($title);

            $content = $fileTitleContent[1];
            $page->setContent($content);

        } else {
            $content = $fileTitleContent[0];
            $page->setContent($content);
        }

        $page->setAlias($contentAlias);

        return $page;
    }
}