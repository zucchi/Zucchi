<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace Zucchi\Form;

use ArrayAccess;
use ReflectionClass;
use Traversable;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Hydrator;
use Zucchi\ServiceManager\ServiceManagerAwareTrait;
use Zend\Form\Factory as ZendFactory;
use Zend\Form\FieldsetInterface;
use Zend\Form\ElementInterface;

/**
 * @category   Zend
 * @package    Zend_Form
 */
class Factory extends ZendFactory
{
    use ServiceManagerAwareTrait;
    /**
     * Prepare and inject a named hydrator
     *
     * Takes a string indicating a hydrator class name (or a concrete instance), instantiates the class
     * by that name, and injects the hydrator instance into the form.
     *
     * @param  string $hydratorOrName
     * @param  FieldsetInterface $fieldset
     * @param  string $method
     * @return void
     * @throws Exception\DomainException If $hydratorOrName is not a string, does not resolve to a known class, or
     *                                   the class does not implement Hydrator\HydratorInterface
     */
    protected function prepareAndInjectHydrator($hydratorOrName, FieldsetInterface $fieldset, $method)
    {
        if (is_object($hydratorOrName) && $hydratorOrName instanceof Hydrator\HydratorInterface) {
            $fieldset->setHydrator($hydratorOrName);
            return;
        }

        if (!is_string($hydratorOrName)) {
            throw new Exception\DomainException(sprintf(
                '%s expects string hydrator class name; received "%s"',
                $method,
                (is_object($hydratorOrName) ? get_class($hydratorOrName) : gettype($hydratorOrName))
            ));
        }

        $sm = $this->getServiceManager();
        if ($sm->has($hydratorOrName)) {;
            $hydrator = $sm->get($hydratorOrName);

        } else if (class_exists($hydratorOrName)) {
            $hydrator = new $hydratorOrName;

        } else {
            throw new Exception\DomainException(sprintf(
                '%s expects string hydrator name to be a valid class name or service; received "%s"',
                $method,
                $hydratorOrName
            ));
        }



        if (!$hydrator instanceof Hydrator\HydratorInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects a valid implementation of Zend\Form\Hydrator\HydratorInterface; received "%s"',
                $method,
                $hydratorOrName
            ));
        }

        $fieldset->setHydrator($hydrator);
    }

    /**
     * Create a fieldset based on the provided specification
     *
     * Specification can contain any of the following:
     * - type: the Fieldset class to use; defaults to \Zend\Form\Fieldset
     * - name: what name to provide the fieldset, if any
     * - options: an array, Traversable, or ArrayAccess object of element options
     * - attributes: an array, Traversable, or ArrayAccess object of element
     *   attributes to assign
     * - elements: an array or Traversable object where each entry is an array
     *   or ArrayAccess object containing the keys:
     *   - flags: (optional) array of flags to pass to FieldsetInterface::add()
     *   - spec: the actual element specification, per {@link createElement()}
     *
     * @param  array|Traversable|ArrayAccess $spec
     * @return FieldsetInterface
     * @throws Exception\InvalidArgumentException for an invalid $spec
     * @throws Exception\DomainException for an invalid fieldset type
     */
    public function createFieldset($spec)
    {
        $spec = $this->validateSpecification($spec, __METHOD__);

        $type = isset($spec['type']) ? $spec['type'] : 'Zend\Form\Fieldset';
        $spec['type'] = $type;

        $fieldset = $this->createElement($spec);
        if (!$fieldset instanceof FieldsetInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects a fieldset type that implements Zend\Form\FieldsetInterface; received "%s"',
                __METHOD__,
                $type
            ));
        }

        if (method_exists($fieldset, 'setFormFactory')) {
            $fieldset->setFormFactory($this);
        }

        if (isset($spec['object'])) {
            $this->prepareAndInjectObject($spec['object'], $fieldset, __METHOD__);
        }

        if (isset($spec['hydrator'])) {
            $this->prepareAndInjectHydrator($spec['hydrator'], $fieldset, __METHOD__);
        }

        if (isset($spec['elements'])) {
            $this->prepareAndInjectElements($spec['elements'], $fieldset, __METHOD__);
        }

        if (isset($spec['fieldsets'])) {
            $this->prepareAndInjectFieldsets($spec['fieldsets'], $fieldset, __METHOD__);
        }

        return $fieldset;
    }

    /**
     * Create an element based on the provided specification
     *
     * Specification can contain any of the following:
     * - type: the Element class to use; defaults to \Zend\Form\Element
     * - name: what name to provide the element, if any
     * - options: an array, Traversable, or ArrayAccess object of element options
     * - attributes: an array, Traversable, or ArrayAccess object of element
     *   attributes to assign
     *
     * @param  array|Traversable|ArrayAccess $spec
     * @return ElementInterface
     * @throws Exception\InvalidArgumentException for an invalid $spec
     * @throws Exception\DomainException for an invalid element type
     */
    public function createElement($spec)
    {
        $spec = $this->validateSpecification($spec, __METHOD__);

        $type       = isset($spec['type'])       ? $spec['type']       : 'Zend\Form\Element';
        $name       = isset($spec['name'])       ? $spec['name']       : null;
        $options    = isset($spec['options'])    ? $spec['options']    : null;
        $attributes = isset($spec['attributes']) ? $spec['attributes'] : null;

        $element = new $type();
        if (!$element instanceof ElementInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects an element type that implements Zend\Form\ElementInterface; received "%s"',
                __METHOD__,
                $type
            ));
        }


        if (method_exists($element, 'setFormFactory')) {
            $element->setFormFactory($this);
        }

        if ($name !== null && $name !== '') {
            $element->setName($name);
        }

        if (is_array($options) || $options instanceof Traversable || $options instanceof ArrayAccess) {
            $element->setOptions($options);
        }

        if (is_array($attributes) || $attributes instanceof Traversable || $attributes instanceof ArrayAccess) {
            $element->setAttributes($attributes);
        }

        return $element;
    }

}
