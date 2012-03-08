<?php
namespace Zucchi\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\Form\Decorator,
    Zend\Form\DisplayGroup;

/**
 * Drop in class to make ZF1 forms fit into Bootstrap CSS framework
 * 
 * Customisation only takes effect when you pass an 
 * actual element object to addElement() method
 * 
 * <code>
 * class CustomForm extends BootstrapForm 
 * {
 *     public init()
 *     {
 *         $this->addElement(new Element\Text('test');
 *     }
 * }
 * </code>
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package Zucchi
 * @subpackage Form
 * @category form
 */
class BootstrapForm extends Form
{
    /**
     * Load the default decorators
     *
     * @return Form
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator(new Decorator\Fieldset())
                 ->addDecorator('FormDecorator');
        }
        return $this;
    }
    
    
    /**
     * override addElement to allwo for drop in replacement 
     * (non-PHPdoc)
     * @see Zend\Form.Form::addElement()
     */
    public function addElement($element, $name = null, $options = null) 
    {
        if ($element instanceof Element\Submit) {
            $this->setupAction($element);
            
        } else if ($element instanceof Element) {
            $this->setupElement($element);
            
        } else {
            parent::addElement($element, $name, $options);
        }
    }
    
    /**
     * setup the passed element and add to the appropriate group
     * @param Element $element
     */
    protected function setupElement(Element $element)
    {
        $element->setAttrib('class', 'input-xlarge');
        $element->clearDecorators();
        $element->addDecorator('ViewHelper');
        $element->addDecorator(new Decorator\Errors(array('class' => 'help-inline')));
        $element->addDecorator(new Decorator\Description(array('tag' => 'p', 'class' => 'help-block')));
        $element->addDecorator(array('controls' => new Decorator\HtmlTag(array('tag' => 'div', 'class' => 'controls'))));
        $element->addDecorator(new Decorator\Label(array('placement' => 'prepend', 'class'=>'control-label')));
        $element->addDecorator(array('control-group' => new Decorator\HtmlTag(array('tag' => 'div', 'class' => 'control-group'))));
        
        parent::addElement($element);
    }
    
    /**
     * setup the passed element and add to the appropriate group
     * @param Element\Submit $element
     */
    protected function setupAction(Element\Submit $element)
    {
            $class = $element->getAttrib('class');
            if ($btn = $element->getAttrib('btn')) {
                $element->setAttrib('class', $class . ' btn btn-' . $btn);
            } else {
                $element->setAttrib('class', $class . ' btn');
            }
            $element->clearDecorators();
            $element->addDecorator('ViewHelper');
        
         if ($group = $this->getDisplayGroup('form-actions')) {
            $group->addElement($element);
        } else {
            $name = $element->getName();
            parent::addElement($element);
            $this->addDisplayGroup(array($name), 'form-actions');
            $group = $this->getDisplayGroup('form-actions');
            $group->clearDecorators();
            $group->addDecorator('FormElements');
            $group->addDecorator(new Decorator\HtmlTag(array('tag' => 'div', 'class' => 'form-actions')));
        }
    }
    
    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {
        $valid = parent::isValid($data);
        if (!$valid) {
            foreach ($this->getElements() AS $el) {
                if ($el->hasErrors()) {
                $decorator = $el->getDecorator('control-group');
                    if ($decorator) {
                        $decorator->setOption('class', 'control-group error');
                    }
                }
            }
        }
        return $valid;
    }
    
    
}