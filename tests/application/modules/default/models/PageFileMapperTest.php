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
 * @group Light_Page
 *
 * @todo refactor find by moving filtering and exception throwing
 * to external, private methods
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
    public function testFind(
        $contentAlias, $lang, $title, $content, $fileContent
    )
    {
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
    public function testFindAliasOrLanguageNotFound()
    {
        //prepare vfs
        $directoryRoot = $this->_getVirtualFsRoot('directoryRootEmpty');

        //setup mapper
        $mapper = new Default_Model_PageFileMapper();
        $mapper->setDirectoryRoot($directoryRoot);

        $contentAlias = 'FooInvalid';
        $lang = 'BarInvalid';
        $pageModel = new Default_Model_Page();

        $mapper->find($contentAlias, $lang, $pageModel);
    }

    /**
     * Alias should not be empty
     * @expectedException Light_Exception_InvalidParameter
     */
    public function testFindEmptyAlias()
    {
        $page = new Default_Model_Page();

        $mapper = new Default_Model_PageFileMapper();
        $mapper->find('', 'foo', $page);
    }

    /**
     * Alias should stay the same after filtering
     * @expectedException Light_Exception_InvalidParameter
     */
    public function testFindMaliciousAlias()
    {
        $page = new Default_Model_Page();

        $mapper = new Default_Model_PageFileMapper();
        $mapper->find('../../../etc/passwd', 'foo', $page);
    }

    /**
     * Language should not be empty
     * @expectedException Light_Exception_InvalidParameter
     */
    public function testFindEmptyLanguage()
    {
        $page = new Default_Model_Page();

        $mapper = new Default_Model_PageFileMapper();
        $mapper->find('foo', '', $page);
    }

    /**
     * Language should stay the same after filtering
     * @expectedException Light_Exception_InvalidParameter
     */
    public function testFindMaliciousLanguage()
    {
        $page = new Default_Model_Page();

        $mapper = new Default_Model_PageFileMapper();
        $mapper->find('passwd', '../../../etc/', $page);
    }

    /**
     * Test filterContentAlias
     *
     * @param string $untrustedAlias
     * @param string $filteredAlias
     * @uses aliasFilterProvider()
     * @dataProvider aliasFilterProvider
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
    public function aliasFilterProvider()
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
     * Language should be filtered to avoid L/RFI, DirTrav, etc.
     *
     * @param string $untrustedLang Language before filtering
     * @param string $filteredLang Language after filtering
     * @uses languageFilterProivder
     * @dataProvider languageFilterProivder
     */
    public function testFilterLanguage($untrustedLang, $filteredLang)
    {
        $mapper = new Default_Model_PageFileMapper();

        $this->assertEquals(
            $filteredLang,
            $mapper->filterLanguage($untrustedLang)
        );
    }

    /**
     * Provides untrusted and filtered language pairs.
     *
     * @return array:array:string
     */
    public function languageFilterProivder()
    {
        return array(
            array('normalLang', 'normalLang'),
            array('norm4l-numbered', 'norm4l-numbered'),
            array('under_score', 'under_score'),
            array('dot.ted', 'dotted'),
            array('slash/ed', 'slashed'),
            array('nulled%00', 'nulled00')
        );
    }

    /**
     * Test if file created on the backend in proper dir with proper content.
     *
     * @dataProvider contentProvider
     */
    public function testSave(
        $contentAlias, $lang, $title, $content, $fileContent
    )
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

    /**
     * Save method needs alias field
     *
     * @expectedException Light_Exception_InvalidParameter
     */
    public function testSaveNoAlias()
    {
        $model = new Default_Model_Page();
        //set language explicitly to avoid catch exception by no language
        $model->setLanguage('bar');

        $mapper = new Default_Model_PageFileMapper();
        $mapper->save($model);
    }

    /**
     * Save method needs language field
     *
     * @expectedException Light_Exception_InvalidParameter
     */
    public function testSaveNoLanguage()
    {
        $model = new Default_Model_Page();
        //set language explicitly to avoid catch exception by no alias
        $model->setAlias('bar');

        $mapper = new Default_Model_PageFileMapper();
        $mapper->save($model);
    }
}