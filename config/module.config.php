<?php
return array(
    'controller_plugins' => array(
        'invokables' => array(
            'messenger' => 'Zucchi\Controller\Plugin\Messenger',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            // generic view helpers
            'truncate' => 'Zucchi\View\Helper\Truncate',
            'filter' => 'Zucchi\View\Helper\Filter',
        ),
        'factories' => array(
            'filter' => function($sm) {
                $helper = new \Zucchi\View\Helper\Filter();
                $helper->setServiceLocator($sm);
                return $helper;
            }
        )
    ),
);