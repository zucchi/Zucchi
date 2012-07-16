<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zucchi\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\View\Helper\FormLabel;
use Zend\Form\View\Helper\FormElement;
use Zend\Form\View\Helper\FormElementErrors;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class BootstrapRow extends AbstractHelper
{
    /**
     * the style of form to generate
     * @var string
     */
    protected $formStyle = 'vertical';
    
    /**
     * templates to use for a bootstrap element
     * 
     * %1$s - label open
     * %2$s - label
     * %3$s - label close
     * %4$s - element
     * %5$s - errors
     * %6$s - help
     * %7$s - status
     * 
     * @var array
     */
    protected $defaultElementTemplates = array(
        'vertical' => '%1$s%2$s%3$s%4$s%5$s',
        'inline' => '%4$s%5$s',
        'search' => '%4$s%5$s',
        'horizontal' => '<div class="control-group %6$s">%1$s%2$s%3$s<div class="controls">%4$s%5$s</div></div>',
    
    );
    
    /**
     * templates used for rendering around an element string
     */
    protected $bootstrapTemplates = array(
        'help' => '<%1$s class="help-%2$s">%3$s</%1$s>',
        'prependAppend' => '<div class="%1$s">%2$s%3$s%4$s</div>',
    );
    
    /**
     * template for element append/prepend
     * 
     * %1$s - prepend/append class
     * %2$s - prepend span
     * %3$s - element
     * %4$s - append span
     * 
     * @var string
     */
    protected $elementPrependAppendTemplate = '<div class="%1$s">%2$s%3$s%4$s</div>';
    
    /**
     * @var array
     */
    protected $labelAttributes;

    /**
     * @var FormLabel
     */
    protected $labelHelper;

    /**
     * @var FormElement
     */
    protected $elementHelper;

    /**
     * @var FormElementErrors
     */
    protected $elementErrorsHelper;
    
    /**
     * element types that act as grouped elements
     * @var array
     */
    protected $groupElements = array(
        'multi_checkbox',
        'multicheckbox',
        'radio',
    );
    
    /**
     * form styles that should be considered as compact
     * @var array
     */
    protected $compactFormStyles = array(
        'inline',
        'search',
    );


    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @param ElementInterface $element
     * @return string
     * @throws \Zend\Form\Exception\DomainException
     */
    public function render(ElementInterface $element)
    {
        $escapeHtmlHelper    = $this->getEscapeHtmlHelper();
        $labelHelper         = $this->getLabelHelper();
        $elementHelper       = $this->getElementHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();
        $label               = $element->getLabel();
        $elementErrors       = $elementErrorsHelper->render($element);
        $elementStatus       = $this->getElementStatus($element);
        $type                = $element->getAttribute('type');
        $bootstrapOptions    = $element->getOption('bootstrap');
        $formStyle           = (isset($bootstrapOptions['style'])) ? $bootstrapOptions['style'] : $this->getFormStyle();
        
        $labelOpen = $labelClose = $labelAttributes = ''; // initialise label variables
        $elementHelp = '';
        
        if (!empty($label)) {
            if (in_array($formStyle, $this->compactFormStyles)) {
                $element->setAttribute('placeholder', $label);
                
            } else {
                
                $label = $escapeHtmlHelper($label);
                $labelAttributes = $element->getLabelAttributes();
    
                if (empty($labelAttributes)) {
                    $labelAttributes = $this->labelAttributes;
                }
                
                $labelAttributes['class'] = isset($labelAttributes['class']) 
                                          ? $labelAttributes['class'] . ' control-label' 
                                          : 'control-label';
                
                $labelOpen  = $labelHelper->openTag($labelAttributes);
                $labelClose = $labelHelper->closeTag();
            }
        }
        
        if (in_array($type, $this->groupElements)) {
            $options = $element->getAttribute('options');
            foreach ($options as $key => $optionSpec) {
                if (!isset($optionSpec['label_attributes']['class'])) {
                    $options[$key]['label_attributes']['class'] = ($type == 'radio') ? 'radio' : 'checkbox';
                    $options[$key]['label_attributes']['class'] .= (in_array($formStyle, $this->compactFormStyles)) ? ' inline' : null;
                }
            }
            $element->setAttribute('options', $options);
        }
        
        
        $elementString       = $elementHelper->render($element);
        
        $elementString = $this->renderBootstrapOptions($elementString, $bootstrapOptions);
        
        $markup = sprintf($this->defaultElementTemplates[$formStyle], 
            $labelOpen,
            $label,
            $labelClose,
            $elementString,
            $elementErrors,
            $elementStatus
        );
    
        
        return $markup;
    }
    

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param null|ElementInterface $element
     * @param null|string $labelPosition
     * @return string|FormRow
     */
    public function __invoke(ElementInterface $element = null, $formStyle = 'vertical')
    {
        if (!$element) {
            return $this;
        }
        
        $this->setFormStyle($formStyle);

        return $this->render($element);
    }

    /**
     * set the style of bootstrap form
     * 
     * @param string $style
     * @return \Zucchi\Form\View\Helper\BootstrapRow
     */
    public function setFormStyle($style)
    {
        $this->formStyle = $style;
        return $this;
    }
    
    /**
     * get the current form style
     * 
     * @return string
     */
    public function getFormStyle()
    {
        return $this->formStyle;
    }
    
    /**
     * Set the attributes for the row label
     *
     * @param  array $labelAttributes
     * @return FormRow
     */
    public function setLabelAttributes($labelAttributes)
    {
        $this->labelAttributes = $labelAttributes;
        return $this;
    }

    /**
     * Get the attributes for the row label
     *
     * @return array
     */
    public function getLabelAttributes()
    {
        return $this->labelAttributes;
    }

    /**
     * Retrieve the FormLabel helper
     *
     * @return FormLabel
     */
    protected function getLabelHelper()
    {
        if ($this->labelHelper) {
            return $this->labelHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->labelHelper = $this->view->plugin('form_label');
        }

        if (!$this->labelHelper instanceof FormLabel) {
            $this->labelHelper = new FormLabel();
        }

        return $this->labelHelper;
    }

    /**
     * Retrieve the FormElement helper
     *
     * @return FormElement
     */
    protected function getElementHelper()
    {
        if ($this->elementHelper) {
            return $this->elementHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->elementHelper = $this->view->plugin('form_element');
        }

        if (!$this->elementHelper instanceof FormElement) {
            $this->elementHelper = new FormElement();
        }

        return $this->elementHelper;
    }

    /**
     * Retrieve the FormElementErrors helper
     *
     * @return FormElementErrors
     */
    protected function getElementErrorsHelper()
    {
        if ($this->elementErrorsHelper) {
            return $this->elementErrorsHelper;
        }
        
        if (method_exists($this->view, 'plugin')) {
            $this->elementErrorsHelper = $this->view->plugin('form_element_errors');
        }

        if (!$this->elementErrorsHelper instanceof FormElementErrors) {
            $this->elementErrorsHelper = new FormElementErrors();
        }

        return $this->elementErrorsHelper;
    }
    
    /**http://hyperion.local.creatingit.co.uk
     * get a string representation of the elements status
     * 
     * @param ElementInterface $element
     * @return string
     */
    public function getElementStatus(ElementInterface $element)
    {
        $status = '';
        if (count($element->getMessages())) {
            $status = ' error ';
        }
        return $status;
    }
    
    /**
     * 
     * @param string $elementString
     * @param array|Traversable $options
     */
    public function renderBootstrapOptions($elementString, $options)
    {
        $escapeHtmlHelper    = $this->getEscapeHtmlHelper();
        
        if (isset($options['prepend']) || isset($options['append'])) {
            $template = $this->bootstrapTemplates['prependAppend'];
            $class = '';
            $prepend = '';
            $append = '';
            if (isset($options['prepend'])) {
                $class .= 'input-prepend ';
                if (!is_array($options['prepend'])) {
                    $options['prepend'] = (array) $options['prepend'];
                }
                foreach ($options['prepend'] AS $p) {
                    $prepend .= '<span class="add-on">' . $escapeHtmlHelper($p) . '</span>';
                }
            }
            if (isset($options['append'])) {
                $class .= 'input-append ';
                if (!is_array($options['append'])) {
                    $options['append'] = (array) $options['append'];
                }
                foreach ($options['append'] AS $a) {
                    $append .= '<span class="add-on">' . $escapeHtmlHelper($a) . '</span>';
                }
            }
            
            $elementString = sprintf($template, 
                $class, 
                $prepend, 
                $elementString, 
                $append);
            
        }
        if (isset($options['help'])) {
            $help = $options['help'];
            $template = $this->bootstrapTemplates['help'];
            $style = 'inline';
            $content = '';
            if (is_array($help)) {
                if (isset($help['style'])) {
                    $style = $help['style'];
                }
                if (isset($help['content'])) {
                    $content = $help['content'];
                }
            } else {
                
                $content = $help;
            }
            
            $tag = $style == 'block' ? 'p' : 'span';
            
            $elementString .= sprintf($template,
                $tag,
                $style,
                $content
            );
            
        }
        
        return $elementString;
        
    }
}
