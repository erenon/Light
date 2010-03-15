<?php
/**
 * Default_Service_Page test
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Page
 * @license    BSD License
 * @version    $Id$
 * @author erenon
 */

require_once '../application/modules/default/services/Page.php';

require_once '../application/modules/default/forms/Page.php';

require_once '../application/modules/default/models/Page.php';

/**
 * Default_Service_Page test suite
 *
 * @category Light
 * @package Light_Test
 * @subpackage Page
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

        $this->assertEquals(
            $pageModel,
            $pageService->getPage()
        );
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
        $this->assertThat(
            $pageService->getMapper(),
            $this->isInstanceOf('Default_Model_PageFileMapper')
        );

        //test database backend
        $this->getMock('Default_Model_PageDbMapper');

        $pageService->setBackend(Default_Service_Page::BACKEND_DATABASE);
        $this->assertThat(
            $pageService->getMapper(),
            $this->isInstanceOf('Default_Model_PageDbMapper')
        );
    }

    /**
     * Throw exception if invalid backend given
     * @expectedException Light_Exception_InvalidParameter
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
        $pageMapper = $this->getMock(
            'Default_Model_PageMapperInterface',
            array('find')
        );

        $pageModel = $this->getMock('Default_Model_Page');

        $pageMapper->expects($this->once())
                  ->method('find')
                  ->with('content-Foo', 'language-ÁŰ', $pageModel);

        $pageService = new Default_Service_Page();
        $pageService->setPage($pageModel);
        $pageService->setMapper($pageMapper);

        $pageService->find('content-Foo', 'language-ÁŰ');
    }

    /**
     * Returns true if form is valid
     */
    public function testSaveReturnsTrueIfValid()
    {
        $form = $this->getMock('Zend_Form', array('isValid'));

        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));

        $mapper = $this->getMock('Mapper', array('save'));

        $pageService = new Default_Service_Page();
        $pageService->setMapper($mapper);
        $pageService->setForm($form);

        $this->assertTrue($pageService->save(array()));
    }

    /**
     * Returns false if form is invalid
     */
    public function testSaveReturnsFalseIfInvalid()
    {
        $form = $this->getMock('Zend_Form', array('isValid'));

        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(false));

        $mapper = $this->getMock('Mapper', array('save'));

        $pageService = new Default_Service_Page();
        $pageService->setMapper($mapper);
        $pageService->setForm($form);

        $this->assertFalse($pageService->save(array()));
    }

    public function testSave()
    {
        $request = array(
            'title'    => 'foo',
            'content'  => 'some content',
            'language' => 'bar'
        );

        $model = new Default_Model_Page($request);
        $model->setAliasFromTitle();

        $form = $this->getMock('Zend_Form', array('isValid', 'getValues'));

        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));

        $form->expects($this->once())
             ->method('getValues')
             ->will($this->returnValue($request));

        $mapper = $this->getMock('Mapper', array('save'));
        $mapper->expects($this->once())
               ->method('save')
               ->with($model);

        $service = new Default_Service_Page();
        $service->setForm($form);
        $service->setMapper($mapper);

        $service->save($request);
    }
}