<?php
/**
 * Page File Mapper test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Page
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

require_once '../application/modules/default/models/PageFileMapper.php';
require_once 'vfsStream/vfsStream.php';
require_once '../application/modules/default/models/Page.php';

/**
 * Page File Mapper test suite
 *
 * @category Light
 * @package Light_Test
 * @subpackage Page
 * @license New BSD License
 * @author erenon
 *
 */
class Application_Modules_Default_Models_PageFileMapperTest
      extends PHPUnit_Framework_TestCase
{
    /**
     * Test set/get DirectoryRoot
     */
    public function testSetDirectoryRoot()
    {
        $directoryRoot = 'foo/bar/baz';

        $mapper = new Default_Model_PageFileMapper();
        $mapper->setDirectoryRoot($directoryRoot);

        $this->assertEquals(
            $directoryRoot,
            $mapper->getDirectoryRoot()
        );
    }

    /**
     * Initialize virtual filesystem
     *
     * @param string $directoryName The root of the vfs
     * @return string The created vfs' root
     */
    protected function _getVirtualFsRoot($directoryName)
    {
        //prepare vfs
        //setup vfsSW, root directory
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory($directoryName));

        return vfsStream::url($directoryName);
    }

    /**
     * Creates a file under the given root in the given directory
     * with the name and content.
     *
     * @param string $root
     * @param string $dir
     * @param string $file
     * @param string $content
     * @return null
     */
    protected function _putVirtualFsContent($root, $dir, $file, $content)
    {
        $dirPath = $root . DIRECTORY_SEPARATOR . $dir;

        mkdir(
            $dirPath,
            0700,
            true
        );

        $fileUri = $dirPath . DIRECTORY_SEPARATOR . $file;

        file_put_contents($fileUri, $content);
    }

    /**
     * Provides test data to testFind()
     */
    public function contentProvider()
    {
        return array(
            array('Bar', 'Foo', 'Title of BAR',
                  "Content of Bar\nMultiline\nContains unicode: ÁÉŰŐ",
                  'Title of BAR'
                  . Default_Model_PageFileMapper::TITLE_CONTENT_SEPARATOR
                  . "Content of Bar\nMultiline\nContains unicode: ÁÉŰŐ"),

            array('Bar2', 'Foo2', null,
                  "Content of Bar-single lined, contains no newline",
                  "Content of Bar-single lined, contains no newline")
        );
    }
    /**
     * find($contentAlias, $lang) should read the file under
     * {page_root}/$lang/$contentAlias
     *
     * @uses _initVirtualBackend
     * @uses contentProvider
     * @dataProvider contentProvider
     */
    public function testFind($contentAlias, $lang, $title, $content, $fileContent)
    {
        /*if (isset($title)) {
            $fileContent = $title . "\n" . $content;
        } else {
            $fileContent = $content;
        }*/

        //setup vfs
        $directoryRoot = $this->_getVirtualFsRoot('directoryRoot');
        $this->_putVirtualFsContent(
            $directoryRoot,
            $lang,
            $contentAlias,
            $fileContent
        );

        //set root directory
        $mapper = new Default_Model_PageFileMapper();
        $mapper->setDirectoryRoot($directoryRoot);

        $pageModel = new Default_Model_Page();

        $mapper->find($contentAlias, $lang, $pageModel);

        if (isset($title)) {
            $this->assertEquals(
                $title,
                $pageModel->getTitle()
            );
        }

        $this->assertEquals(
            $contentAlias,
            $pageModel->getAlias()
        );

        $this->assertEquals(
            $content,
            $pageModel->getContent()
        );

        $this->assertEquals(
            $lang,
            $pageModel->getLanguage()
        );
    }

    /**
     * Throw exception if nonexisting lang or alias given
     * @expectedException Light_Exception_NotFound
     */
    public function testFindInvalid()
    {
        //prepare vfs
        $directoryRoot = $this->_getVirtualFsRoot('directoryRootInvalid');

        //setup mapper
        $mapper = new Default_Model_PageFileMapper();
        $mapper->setDirectoryRoot($directoryRoot);

        $lang = 'BarInvalid';
        $contentAlias = 'FooInvalid';
        $pageModel = new Default_Model_Page();

        $mapper->find($lang, $contentAlias, $pageModel);
    }

    /**
     * Test filterContentAlias
     *
     * @param string $untrustedAlias
     * @param string $filteredAlias
     * @uses aliasProvider()
     * @dataProvider aliasProvider
     */
    public function testFilterContentAlias($untrustedAlias, $filteredAlias)
    {
        $mapper = new Default_Model_PageFileMapper();

        $this->assertEquals(
            $filteredAlias,
            $mapper->filterContentAlias($untrustedAlias)
        );
    }

    /**
     * Provides test data to testFilterContentAlias()
     *
     * @return array:array:string
     */
    public function aliasProvider()
    {
        return array(
            array('normalAlias', 'normalAlias'),
            array('file.txt', 'file.txt'),
            array('file.html', 'file.html'),
            array('file.php', 'file'),
            array('file.php.php', 'filephp'),
            array('../file', 'file'),
            array('../../../../file', 'file'),
            array("file%00", 'file00'),
            //array('Fájl', 'Fájl'), //mulibyte chars are not allowed
            array('numb3rs', 'numb3rs'),
            array('under_scored', 'under_scored'),
            array('dash-ed', 'dash-ed')
        );
    }

    /**
     * Test if file created on the backend in proper dir with proper content.
     *
     * @dataProvider contentProvider
     */
    public function testSave($contentAlias, $lang, $title, $content, $fileContent)
    {
        $model = new Default_Model_Page();
        $model->setAlias($contentAlias)
              ->setTitle($title)
              ->setContent($content)
              ->setLanguage($lang);

        $mapper = new Default_Model_PageFileMapper();

        $directoryRoot = $this->_getVirtualFsRoot('dirToSave');

        $mapper->setDirectoryRoot($directoryRoot);

        $mapper->save($model);

        $fileUri = $directoryRoot
                 . DIRECTORY_SEPARATOR
                 . $lang
                 . DIRECTORY_SEPARATOR
                 . $contentAlias;

        $this->assertFileExists($fileUri);

        $this->assertEquals(
            $fileContent,
            file_get_contents($fileUri)
        );
    }
}