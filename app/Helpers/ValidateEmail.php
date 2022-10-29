<?php

namespace App\Helpers;

class ValidateEmail{

    public static function check($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}