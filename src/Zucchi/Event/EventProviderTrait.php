<?php

namespace Zucchi\Event;

use Traversable;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

// annotation namespaces
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation AS Form;

trait EventProviderTrait
{
    /**
     * @var EventManagerInterface
     * @Form\Exclude
     */
    protected $events;

    /**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(
            current(explode('\\', get_called_class())),// base namespace
            __CLASS__, //  
            get_called_class() // called class
        );
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}