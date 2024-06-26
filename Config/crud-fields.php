<?php

return [
    'formFields' => [
        'title' => [
            'value' => null,
            'name' => 'title',
            'type' => 'input',
            'isTranslatable' => true,
            'props' => [
                'label' => 'icommerce::common.title',
            ],
        ],
        'description' => [
            'value' => null,
            'name' => 'description',
            'type' => 'input',
            'isTranslatable' => true,
            'props' => [
                'label' => 'icommerce::common.description',
                'type' => 'textarea',
                'rows' => 3,
            ],
        ],
        'status' => [
            'value' => 0,
            'name' => 'status',
            'type' => 'select',
            'props' => [
                'label' => 'icommerce::common.status',
                'useInput' => false,
                'useChips' => false,
                'multiple' => false,
                'hideDropdownIcon' => true,
                'newValueMode' => 'add-unique',
                'options' => [
                    ['label' => 'Activo', 'value' => 1],
                    ['label' => 'Inactivo', 'value' => 0],
                ],
            ],
        ],
        'mainimage' => [
            'value' => (object) [],
            'name' => 'mediasSingle',
            'type' => 'media',
            'props' => [
                'label' => 'Image',
                'zone' => 'mainimage',
                'entity' => "Modules\Icommerce\Entities\PaymentMethod",
                'entityId' => null,
            ],
        ],
        'init' => [
            'value' => 'Modules\Icommercebraintree\Http\Controllers\Api\IcommerceBraintreeApiController',
            'name' => 'init',
            'isFakeField' => true,
        ],
        'merchantId' => [
            'value' => null,
            'name' => 'merchantId',
            'isFakeField' => true,
            'type' => 'input',
            'props' => [
                'label' => 'icommercebraintree::icommercebraintrees.table.merchantId',
            ],
        ],
        'publicKey' => [
            'value' => null,
            'name' => 'publicKey',
            'isFakeField' => true,
            'type' => 'input',
            'props' => [
                'label' => 'icommercebraintree::icommercebraintrees.table.publicKey',
            ],
        ],
        'privateKey' => [
            'value' => null,
            'name' => 'privateKey',
            'isFakeField' => true,
            'type' => 'input',
            'props' => [
                'label' => 'icommercebraintree::icommercebraintrees.table.privateKey',
            ],
        ],
        'mode' => [
            'value' => 'sandbox',
            'name' => 'mode',
            'isFakeField' => true,
            'type' => 'select',
            'props' => [
                'label' => 'icommercebraintree::icommercebraintrees.table.mode',
                'useInput' => false,
                'useChips' => false,
                'multiple' => false,
                'hideDropdownIcon' => true,
                'newValueMode' => 'add-unique',
                'options' => [
                    ['label' => 'Sandbox', 'value' => 'sandbox'],
                    ['label' => 'Production', 'value' => 'production'],
                ],
            ],
        ],
        'minimunAmount' => [
            'value' => null,
            'name' => 'minimunAmount',
            'isFakeField' => true,
            'type' => 'input',
            'props' => [
                'label' => 'icommerce::common.minimum Amount',
            ],
        ],

    ],

];
