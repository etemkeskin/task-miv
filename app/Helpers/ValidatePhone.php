<?php

namespace App\Helpers;

class ValidatePhone{

    const PHONE_NUMBER_LENGTH = 10;

    public static function check($phone)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone );

        if(strlen($phone) == self::PHONE_NUMBER_LENGTH){
            return (int)$phone;
        }

        $firstCharacter = (int)substr($phone, 0, 1);
        if(strlen($phone) === (self::PHONE_NUMBER_LENGTH +1)  && ($firstCharacter === 0)){
            return (int)substr($phone, 1);
        }

        return false;
    }
}