<?php
/**
 * Simple form to create and edit Pages
 *
 * @category   Light
 * @package    Light_Page
 * @subpackage Form
 * @license    New BSD License
 * @version    $Id:$
 * @author erenon
 */

require_once 'Zend/Form.php';

class Default_Form_Page extends Zend_Form
{
    /**
     * Initalize form elements
     * @return void
     */
    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);

        $this->addElement(
            'text',
            'title',
            array(
                'label'	=> 'Title',
            	'required' => true,
                'validators' => array(
                    array('StringLength', false, array(1, 512))
                )
            )
        );

        $this->addElement(
            'text',
            'alias',
            array(
                'label'	=> 'Alias',
            	'required' => false,
                'validators' => array(
                    array('StringLength', false, array(1, 512))
                )
            )
        );

        $this->addElement(
            'textarea',
            'content',
            array(
                'label'	=> 'Content',
            	'required' => true,
            )
        );

        $this->addElement(
            'text',
            'language',
            array(
                'label'	=> 'Language',
            	'required' => true,
                'validators' => array(
                    array('StringLength', false, array(1, 64))
                )
            )
        );

        $this->addElement(
            'submit',
            'submit',
            array(
                'label' => 'Submit'
            )
        );
    }
}