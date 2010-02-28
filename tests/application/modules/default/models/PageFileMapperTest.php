<?php
/**
 * Page File Mapper test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Light_Test_Page
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
 * @subpackage Light_Test_Page
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

        $this->assertEquals($directoryRoot,
                           $mapper->getDirectoryRoot());
    }

    protected function _initVirtualBackend($lang)
    {
        //prepare vfs
        //setup vfsSW, root directory
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('directoryRoot'));

        //define root directory
        $directoryRoot = vfsStream::url('directoryRoot');

        $contentDirectory = $directoryRoot . DIRECTORY_SEPARATOR . $lang;
        mkdir($contentDirectory,
              0700,
              true);

        return array($directoryRoot, $contentDirectory);
    }

    public function contentProvider()
    {
        return array(
            array('Bar', 'Foo', 'Title of BAR', "Content of Bar\nMultiline\nContains unicode: ÁÉŰŐ"),
            array('Bar', 'Foo', null, "Content of Bar-single lined, contains no newline")
        );
    }
    /**
     * find($contentAlias, $lang) should read the file under
     * {page_root}/$lang/$contentAlias
     * @dataProvider contentProvider
     */
    public function testFind($contentAlias, $lang, $title, $content)
    {
        if (isset($title)) {
            $fileContent = $title . "\n" . $content;
        } else {
            $fileContent = $content;
        }

        //setup vfs
        $virtualDir = $this->_initVirtualBackend($lang);
        $directoryRoot = $virtualDir[0];
        $contentDirectory = $virtualDir[1];

        $contentPath = $contentDirectory . DIRECTORY_SEPARATOR . $contentAlias;
        file_put_contents($contentPath, $fileContent);

        //set root directory
        $mapper = new Default_Model_PageFileMapper();
        $mapper->setDirectoryRoot($directoryRoot);

        $pageModel = new Default_Model_Page();

        $mapper->find($contentAlias, $lang, $pageModel);

        if (isset($title)) {
            $this->assertEquals($title,
                                $pageModel->getTitle());
        }

        $this->assertEquals($contentAlias,
                            $pageModel->getAlias());

        $this->assertEquals($content,
                            $pageModel->getContent());
    }

    /**
     * Test exception if invalid path given
     * @expectedException Exception
     */
    public function testFindInvalid()
    {
        //prepare vfs
        //setup vfsSW, root directory
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('directoryRootInvalid'));

        //define root directory
        $directoryRoot = vfsStream::url('directoryRootInvalid');

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
     * @dataProvider aliasProvider
     */
    public function testFilterContentAlias($untrustedAlias, $filteredAlias)
    {
        $mapper = new Default_Model_PageFileMapper();

        $this->assertEquals($filteredAlias,
                            $mapper->filterContentAlias($untrustedAlias));
    }

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
            //@todo what about the /u modifier?
            array('numb3rs', 'numb3rs'),
            array('under_scored', 'under_scored'),
            array('dash-ed', 'dash-ed')
        );
    }
}