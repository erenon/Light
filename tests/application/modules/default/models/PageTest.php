<?php
/**
 * Default_Model_Page test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Light_Test_Page
 * @license    New BSD License
 * @version    $Id$
 * @author erenon
 */

require_once '../application/modules/default/models/Page.php';

/**
 * Default_Model_Page test suite
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
class Application_Modules_Default_Models_PageTest
      extends PHPUnit_Framework_TestCase
{
    /**
     * Test if model's find calls mapper's find with proper arguments
     */
    public function testFindGetCalled()
    {
        $pageMapper = $this->getMock('Default_Model_PageMapper',
                                    array('find'));

        $pageModel = new Default_Model_Page();

        $pageMapper->expects($this->once())
                  ->method('find')
                  ->with('content-Foo', 'language-ÁŰ', $pageModel);

        $pageModel->setMapper($pageMapper);

        $pageModel->find('content-Foo', 'language-ÁŰ');
    }

    /**
     * Test if setBackend sets up the proper mapper
     */
    public function testSetBackend()
    {
        $pageModel = new Default_Model_Page();

        //test file backend
        $this->getMock('Default_Model_PageFileMapper');

        $pageModel->setBackend(Default_Model_Page::BACKEND_FILE);
        $this->assertThat($pageModel->getMapper(),
                          $this->isInstanceOf('Default_Model_PageFileMapper'));

        //test database backend
        $this->getMock('Default_Model_PageDbMapper');

        $pageModel->setBackend(Default_Model_Page::BACKEND_DATABASE);
        $this->assertThat($pageModel->getMapper(),
                          $this->isInstanceOf('Default_Model_PageDbMapper'));
    }

    /**
     * Throw exception if invalid backend given
     * @expectedException Exception
     */
    public function testSetBackendInvalid()
    {
        $pageModel = new Default_Model_Page();
        $pageModel->setBackend('invalid_backend');
    }
}