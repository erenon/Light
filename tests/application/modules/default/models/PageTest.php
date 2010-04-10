<?php
/**
 * $LICENSE$
 *
 * @category   Light
 * @package    Light_Test
 * @subpackage Page
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
 * @subpackage Page
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
     * Test alias generator
     *
     * @dataProvider provideTitleAlias
     */
    public function testSetAliasFromTitle($title, $alias)
    {
        $page = new Default_Model_Page();
        $page->setTitle($title);
        $page->setAliasFromTitle();

        $this->assertEquals(
            $alias,
            $page->getAlias()
        );
    }

    /**
     * Test generation of alias from title
     */
    public function provideTitleAlias()
    {
        return array(
            array('white space', 'white-space'),
            array("white space\ttab\nnewline\rreturn",
                  'white-space-tab-newline-return'),
            array('árvíztűrőtükörfúrógép',
                  'arvizturotukorfurogep'),
            array('ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP',
                  'ARVIZTUROTUKORFUROGEP'),
            array('?&',
                  urlencode('?&'))
        );
    }
}