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
     * @throws Light_Exception_NotFound If file not found or not readable
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
            require_once 'Light/Exception/NotFound.php';
            throw new Light_Exception_NotFound('File not found or not readable');
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
        $page->setLanguage($lang);

        return $page;
    }

    /**
     * Persists the given model on the backend.
     *
     * Creates a language dir if not presented, and writes a file into it.
     * Filename is the page alias.
     *
     * @param Default_Model_Page $page Page model to save
     * @return bool true
     * @throws Light_Exception_InvalidParameter If language or alias not presented in the model
     */
    public function save(Default_Model_Page $page)
    {
        if ("" == $page->getLanguage()) {
            require_once 'Light/Exception/InvalidParameter.php';
            throw new Light_Exception_InvalidParameter("Language not provided");
        }

        if ("" == $page->getAlias()) {
            require_once 'Light/Exception/InvalidParameter.php';
            throw new Light_Exception_InvalidParameter("Alias not provided");
        }

        $fileUri = $this->getDirectoryRoot()
                 . DIRECTORY_SEPARATOR
                 . $page->getLanguage()
                 . DIRECTORY_SEPARATOR
                 . $page->getAlias();

        $fileContent = $this->_contentFromTitleAndContent(
            $page->getTitle(),
            $page->getContent()
        );

        $this->_createDirIfNeeded($page->getLanguage());

        file_put_contents(
            $fileUri,
            $fileContent
        );

        return true;
    }

    /**
     * Creates the given directory on the backend, if not presented any.
     *
     * @param string $dir Directory to create
     * @return bool true
     * @throws Light_Exception_Resource If backend dir is not writable
     */
    private function _createDirIfNeeded($dir)
    {
        $root = $this->getDirectoryRoot();
        $neededDir = $root
                   . DIRECTORY_SEPARATOR
                   . $dir;

        if (false === is_dir($neededDir)) {
            if (is_writable($root)) {
                mkdir(
                    $neededDir,
                    0700,
                    true
                );
            } else {
                require_once 'Light/Exception/Resource.php';
                throw new Light_Exception_Resource("Backend directory root is not writable");
            }
        }

        return true;
    }

    /**
     * Concates title, separator and content, if title is not empty
     *
     * @param string $title
     * @param string $content
     * @return string The created string, or content if title was empty
     * @uses TITLE_CONTENT_SEPARATOR
     */
    private function _contentFromTitleAndContent($title, $content)
    {
        if (false != $title) {
            $content = $title
                     . self::TITLE_CONTENT_SEPARATOR
                     . $content;

        }

        return $content;
    }
}