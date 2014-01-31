<?php

namespace Zucchi;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements 
    AutoloaderProviderInterface,
    ConfigProviderInterface
    
{

    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        $serviceLoader = $sm->get('ServiceManager');
        $serviceLoader->addInitializer(function ($instance) use ($sm) {
            if (method_exists($instance, 'setServiceManager')) {
                $instance->setServiceManager($sm);
            }
        });
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'filter' => function($sm)
                {
                    $helper = new \Zucchi\View\Helper\Filter();
                    $helper->setServiceLocator($sm);
                    return $helper;
                }
            )
        );
    }

}
