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
    /**
     * find($contentAlias, $lang) should read the file under
     * {page_root}/$lang/$contentAlias
     * @todo refactor vfsSW preaparations into separate method
     * @todo add testFindNoTitle
     */
    public function testFind()
    {
        //contentAlias
        $contentAlias = 'Bar';

        //language
        $lang = 'Foo';

        $title = 'Title of BAR';
        $content = "Content of Bar\nMultiline\nContains unicode: ÁÉŰŐ";

        $fileContent = $title . "\n" . $content;

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

        $contentPath = $contentDirectory . DIRECTORY_SEPARATOR . $contentAlias;

        file_put_contents($contentPath, $fileContent);

        //set root directory
        $mapper = new Default_Model_PageFileMapper();
        $mapper->setDirectoryRoot($directoryRoot);

        $pageModel = new Default_Model_Page();

        $mapper->find($contentAlias, $lang, $pageModel);

        $this->assertEquals($title,
                            $pageModel->getTitle());

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
}