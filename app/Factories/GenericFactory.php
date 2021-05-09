<?php

namespace App\Factories;



class GenericFactory {

    public function __construct()
    {

    }

    public function getInstance($class,  $data = [])
    {
        return new $class;
    }
}
