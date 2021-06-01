<?php

namespace App\Factories;



class GenericFactory {

    public function __construct()
    {

    }

    public function getInstance($class,  $data = [])
    {
        return app($class, $data);
    }
}
