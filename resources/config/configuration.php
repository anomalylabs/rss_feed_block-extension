<?php

return [
    'url'                 => [
        'required' => true,
        'type'     => 'anomaly.field_type.url',
    ],
    'count'               => [
        'required' => true,
        'type'     => 'anomaly.field_type.integer',
        'config'   => [
            'min'           => 1,
            'default_value' => 5,
        ],
    ],
    'enable_descriptions' => 'anomaly.field_type.boolean',
];
