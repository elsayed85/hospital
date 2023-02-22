<?php

return [
    'name' => 'Doctor',
    "roles" => [
        "regular",
        "admin",
        "super admin",
    ],
    "permissions" =>  [
        "admin" => [
            "show roles",

            "show permissions",

            "show doctors",
            "create doctors",
            "edit doctors",
            "delete doctors",

            "show nurses",
            "create nurses",
            "edit nurses",
            "delete nurses",
        ],
        "super_admin" => [
            "show roles",
            "create roles",
            "edit roles",
            "delete roles",

            "show permissions",

            "show doctors",
            "create doctors",
            "edit doctors",
            "delete doctors",
            "restore doctors",

            "show nurses",
            "create nurses",
            "edit nurses",
            "delete nurses",
            "restore nurses",
        ],
    ],
];
