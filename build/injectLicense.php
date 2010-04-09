<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Build
 * @subpackage Formatter
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */


/**
 * License injector
 *
 * Iterates over the project files and replaces the first
 * occurence of the license token to the license text
 * in all of the files
 *
 * @category   Light
 * @package    Light_Build
 * @subpackage Formatter
 * @license New BSD License
 * @author erenon
 *
 */
class Light_Build_InjectLicense
{
    /**
     * The name of the license file
     * in the project root
     *
     * @var string
     */
    const LICENSE_FILE = 'LICENSE';

    /**
     * The regex form of the license token
     * to replace with license text
     *
     * @var string
     */
    const LICENSE_TOKEN = '/\$LICENSE\$/';

    /**
     * Project root
     *
     * @var string
     */
    private $_applicationRoot;

    /**
     * The license text to inject
     *
     * @var string
     */
    private $_licenseText;

    /**
     * Extensions to search for
     *
     * @var array
     */
    private $_allowedExtensions = array('php');

    /**
     * Sets up application root, inits the license text and calls iterate
     */
    public function __construct()
    {
        $this->_applicationRoot = realpath(
            dirname(__FILE__) .
            DIRECTORY_SEPARATOR .
            '..'
        ) .
        DIRECTORY_SEPARATOR;

        $this->_initLIcenseText();
        $this->_iterate();
    }

    /**
     * Reads the license file
     * and stores it in $this->_licenseText
     *
     * @throws Exception if the license file not found
     */
    private function _initLicenseText()
    {
        $file = $this->_applicationRoot .
                self::LICENSE_FILE;

        if (is_file($file)) {
            $text = file_get_contents($file);
        } else {
            throw new Exception('License file not found');
        }

        $text = str_replace(
            "\n",
            "\n * ",
            $text
        );

        $this->_licenseText = $text;
    }

    /**
     * Iterates over the project files
     * and calls replace token on each
     *
     * @uses pear.phpunit.de/File_Iterator
     */
    private function _iterate()
    {
        require_once 'File/Iterator/Factory.php';

        $files = File_Iterator_Factory::getFilesAsArray(
            $this->_applicationRoot,
            $this->_allowedExtensions
        );

        foreach ($files as $file) {
            $this->_replaceToken($file);
        }

    }

    /**
     * Replaces the first occurence of the license token
     * to the license text
     * in the given file
     *
     * @param string $file file to examine
     */
    private function _replaceToken($file)
    {
        $oldContent = file_get_contents($file);

        $newContent = preg_replace(
            self::LICENSE_TOKEN,
            $this->_licenseText,
            $oldContent,
            1
        );

        if ($oldContent !== $newContent) {
            file_put_contents($file, $newContent);
        }
    }
}

new Light_Build_InjectLicense();