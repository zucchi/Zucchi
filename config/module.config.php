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
            'form_element' => 'Zucchi\Form\View\Helper\FormElement',
            'form_imageFile' => 'Zucchi\Image\Form\View\Helper\ImageFile',
        ),
    ),
);
