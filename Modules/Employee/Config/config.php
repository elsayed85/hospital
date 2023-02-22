<?php

return [
    'name' => 'Employee',
    'permissions' => [
        "admin" => [
            "show roles",
            "create roles",
            "edit roles",
            "delete roles",

            "show permissions",
        ],
        "super_admin" => [
            "show roles",
            "create roles",
            "edit roles",
            "delete roles",
            "restore roles",

            "show permissions",
        ]
    ]
];
