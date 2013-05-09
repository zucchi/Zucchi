<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiAdmin for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace Zucchi\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Filter string
 *
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package Zucchi
 * @subpackage View
 * @category Helper
 */
class Filter extends AbstractHelper implements 
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    
    protected $pluginManager;
    
    /**
     * filter string
     *
     * @param string $text
     * @param string $filter
     * @param bool $escape
     * @return string
     */
    public function __invoke($text, $filters, $escape = true)
    {
        if (!is_array($filters)) {
            $filters = array($filters);
        }
        
        foreach ($filters as $filter) {
            $filter = $this->getPluginManager()->get($filter);
            $text = $filter->filter($text);
        }
        
        return ($escape ? $this->view->escapeHtml($text) : $text);
    
    }
    
    
    public function getPluginManager()
    {
        if (!$this->pluginManager) {
            $this->pluginManager = new FilterPluginManager();
        }
        return $this->pluginManager;
    }
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
