<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Page
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once '../application/modules/default/forms/Page.php';

/**
 * Default_Form_Page test suite
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
class Application_Modules_Default_Forms_PageTest
      extends PHPUnit_Framework_TestCase
{
    /**
     * Test form method is post
     */
    public function testMethod()
    {
        $form = new Default_Form_Page();

        $this->assertEquals(
            'post',
            $form->getMethod()
        );
    }

    /**
     * Test the needed element is provided
     */
    public function testElements()
    {
        $neededElements = array(
            'title',
            'alias',
            'content',
            'language'
        );

        $form = new Default_Form_Page();

        foreach ($neededElements as $element) {
            $this->assertThat(
                $form->getElement($element),
                $this->isInstanceOf('Zend_Form_Element')
            );
        }

    }

    /**
     * Provides element name-length pairs
     *
     * @see testElementLengths
     */
    public function elementLengthProvider()
    {
        return array(
            array('title', 512),
            array('alias', 512),
            array('language', 64)
        );
    }

    /**
     * Test allowed lenghts
     *
     * @param string $element Element name
     * @param int $length Element max length
     * @dataProvider elementLengthProvider
     */
    public function testElementLengths($element, $length)
    {
        $value = str_repeat('a', $length);

        $form = new Default_Form_Page();
        $input = array($element => $value);

        //max length should be allowed
        $this->assertTrue(
            $form->isValidPartial($input)
        );

        //max length+1 is not valid
        $input[$element] .= 'a';

        $this->assertFalse(
            $form->isValidPartial($input)
        );
    }

    /**
     * Provides element name-isRequired pairs
     *
     * @see testElementRequired
     */
    public function elementRequiredProvided()
    {
        return array(
            array('title', true),
            array('alias', false),
            array('content', true),
            array('language', true)
        );
    }

    /**
     * Test element is required
     *
     * @param string $element Element name
     * @param bool $isRequired true is required
     *
     * @dataProvider elementRequiredProvided
     */
    public function testElementRequired($element, $isRequired)
    {
        $form = new Default_Form_Page();
        $element = $form->getElement($element);

        $this->assertEquals(
            $isRequired,
            $element->isRequired()
        );
    }
}