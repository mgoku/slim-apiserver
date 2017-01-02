<?php

namespace mgoku\apiserver\controller;

class BaseController
{
    /* Jenis protected agar visible di child class */
    protected $ci;

    /* Init class dan inject container instance */
    public function __construct ($ci) {
        $this->ci = $ci;
    }
}