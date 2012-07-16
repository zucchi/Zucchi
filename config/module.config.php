<?php
return array(
    'view_helpers' => array(
        'invokables' => array(
            // generic view helpers
            'truncate' => 'Zucchi\View\Helper\Truncate',

            // form based view helpers
            'bootstrapForm' => 'Zucchi\Form\View\Helper\BootstrapForm',
            'bootstrapRow' => 'Zucchi\Form\View\Helper\BootstrapRow',
            'bootstrapCollection' => 'Zucchi\Form\View\Helper\BootstrapCollection',
        ),
    ),
);