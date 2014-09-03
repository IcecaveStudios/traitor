<?php
namespace Icecave\Traitor;

class ParentClass
{
    public function __construct()
    {
        $this->arguments = func_get_args();
    }

    public $arguments;
}
