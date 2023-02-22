<?php

use Modules\Doctor\Entities\Doctor;
use Modules\Employee\Entities\Employee;
use Modules\Nurse\Entities\Nurse;

$key = "login";
return [
    "types" => [
        // doctor
        "doctor" => [
            "model" => Doctor::class,
            "guard" => "doctor",
            "login" => "/login/doctor",
            "name" => "$key.doctor",
            "hint" => "$key.doctor_hint"
        ],
        // nurse
        "nurse" => [
            "model" => Nurse::class,
            "guard" => "nurse",
            "login" => "/login/nurse",
            "name" => "$key.nurse",
            "hint" => "$key.nurse_hint"
        ],
        // employee
        "employee" => [
            "model" => Employee::class,
            "guard" => "employee",
            "login" => "/login/employee",
            "name" => "$key.employee",
            "hint" => "$key.employee_hint"
        ],
    ]
];
