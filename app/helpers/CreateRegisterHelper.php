<?php
namespace App\Helpers;

class CreateRegisterHelper {

    public static function isEditing(Array $request, $id = 'id')
    {
        return isset($request[$id]);

    }
}