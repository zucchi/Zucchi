<?php
namespace Zucchi\Form\View\Helper;

use Zend\Form\View\Helper\FormElement as ZendFormElement;

use Zend\Form\Element;
use Zend\Form\ElementInterface;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 */
class FormElement extends ZendFormElement
{
    /**
     * Render an element
     *
     * Introspects the element type and attributes to determine which
     * helper to utilize when rendering.
     *
     * @param  ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        $type = $element->getAttribute('type');

        if (!empty($type)) {
            $pm = $renderer->getHelperPluginManager();

            if ($pm->has('form_' . $type)) {
                $helper = $pm->get('form_' . $type);
                return $helper($element);
            }
        }

        return parent::render($element);
    }
}
