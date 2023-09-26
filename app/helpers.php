<?php

use App\Models\Admin;

if (!function_exists('addAdmin')) {
    function addAdmin($name, $lastName, $email, $password)
    {
        Admin::create([
            'first_name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'password' => bcrypt($password)
        ]);
    }
}
