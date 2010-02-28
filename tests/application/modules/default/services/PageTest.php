<?php
/**
 * Default_Service_Page test
 *
 * @category   Light
 * @package    Light_Test
 * @license    BSD License
 * @version    $Id$
 * @author erenon
 */

require_once '../application/modules/default/services/Page.php';

/**
 * Default_Service_Page test suite
 *
 * @category Light
 * @package Light_Test
 * @subpackage Light_Test_Page
 * @license New BSD License
 * @author erenon
 *
 * @group Light_Page
 *
 */
class Application_Modules_Default_Services_PageTest
      extends PHPUnit_Framework_TestCase
{
    /**
     * Test $_page getters and setters
     *
     * @see http://github.com/erenon/Light/issues#issue/1
     * @ticket 1
     */
    public function testGetSetPage()
    {
        $pageModel = $this->getMock('Default_Model_Page');

        $pageService = new Default_Service_Page();
        $pageService->setPage($pageModel);

        $this->assertEquals($pageModel,
                            $pageService->getPage());
    }

    /**
     * Test if setBackend sets up the proper mapper
     */
    public function testSetBackend()
    {
        $pageService = new Default_Service_Page();

        //test file backend
        $this->getMock('Default_Model_PageFileMapper');

        $pageService->setBackend(Default_Service_Page::BACKEND_FILE);
        $this->assertThat($pageService->getMapper(),
                          $this->isInstanceOf('Default_Model_PageFileMapper'));

        //test database backend
        $this->getMock('Default_Model_PageDbMapper');

        $pageService->setBackend(Default_Service_Page::BACKEND_DATABASE);
        $this->assertThat($pageService->getMapper(),
                          $this->isInstanceOf('Default_Model_PageDbMapper'));
    }

    /**
     * Throw exception if invalid backend given
     * @expectedException Exception
     */
    public function testSetBackendInvalid()
    {
        $pageService = new Default_Service_Page();
        $pageService->setBackend('invalid_backend');
    }

    /**
     * Test if Service's find calls mapper's find with proper arguments
     *
     * @see http://github.com/erenon/Light/issues#issue/2
     * @ticket 2
     */
    public function testFindGetCalled()
    {
        $pageMapper = $this->getMock('Default_Model_PageMapperInterface',
                                    array('find'));

        $pageModel = $this->getMock('Default_Model_Page');

        $pageMapper->expects($this->once())
                  ->method('find')
                  ->with('content-Foo', 'language-ÁŰ', $pageModel);

        $pageService = new Default_Service_Page();
        $pageService->setPage($pageModel);
        $pageService->setMapper($pageMapper);

        $pageService->find('content-Foo', 'language-ÁŰ');
    }
}