<?php

return [
    'default_status' => 'open',

    'priorities' => ['low', 'medium', 'high', 'urgent'],

    'statuses' => ['open', 'in_progress', 'review', 'completed', 'cancelled'],

    'categories' => [
        'enabled' => true,
    ],

    'dependencies' => [
        'enabled' => true,
        'max_depth' => 5,
    ],

    'comments' => [
        'enabled' => true,
    ],

    'overdue' => [
        'check_hours' => 1,
        'auto_mark' => true,
    ],
];
