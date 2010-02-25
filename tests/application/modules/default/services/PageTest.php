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

        $returnedPageModel = $pageService->getPage();

        $this->assertEquals($pageModel, $returnedPageModel);
    }

    /**
     * Test if Service's find calls model's find with proper arguments
     *
     * @see http://github.com/erenon/Light/issues#issue/2
     * @ticket 2
     */
    public function testFindGetCalled()
    {
        $pageModel = $this->getMock('Default_Model_Page',
                                    array('find'));

        $pageModel->expects($this->once())
                  ->method('find')
                  ->with('content-Foo', 'language-ÁŰ');

        $pageService = new Default_Service_Page();
        $pageService->setPage($pageModel);

        $pageService->find('content-Foo', 'language-ÁŰ');
    }
}