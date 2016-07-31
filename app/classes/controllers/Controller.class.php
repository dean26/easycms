<?php



class Controller
{

    protected $slim;

    public function __construct(Slim\Container $ci) {
        $this->slim = $ci;
    }

}
