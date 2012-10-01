<?php

namespace Zucchi\ServiceManager;

use Traversable;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;


trait ServiceManagerAwareTrait
{
    /**
     * @var EventManagerInterface
     */
    protected $serviceManager;

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}