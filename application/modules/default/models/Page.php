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

require_once 'Light/Model/Abstract.php';

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
class Default_Model_Page extends Light_Model_Abstract
{
    private $_id;
    private $_title;
    private $_alias;
    private $_content;
    private $_language;

    const ALIAS_WHITESPACE_REPLACEMENT = '-';

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

    public function setLanguage($language)
    {
      $this->_language = (string) $language;
      return $this;
    }

    public function getLanguage()
    {
      return $this->_language;
    }

    /**
     * Calculates the alias of the page based on it's title.
     *
     * This method replaces whitespaces with dashes,
     * partially removes accents,
     * and use urlencode.
     *
     * @return $this
     */
    public function setAliasFromTitle()
    {
        $title = $this->getTitle();

        if (empty($title)) {
            return $this;
        }

        //remove whitespaces from the beginning and end of title
        $alias = trim($title);
        $alias = preg_replace(
            '/\s/',
            self::ALIAS_WHITESPACE_REPLACEMENT,
            $alias
        );

        //remove accented chars
        $alias = iconv('UTF-8', 'US-ASCII//TRANSLIT', $alias);
        //iconv represents accents as other chars, let's strip them as well
        $alias = str_replace(array('\'', '"', ':'), '', $alias);

        //safety first: url encode
        $alias = urlencode($alias);

        $this->setAlias($alias);

        return $this;
    }
}