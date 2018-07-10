<?php
namespace PDoc\Entities;

class UseAlias
{
    public $name;
    public $alias;
    public function __construct(string $name, string $alias)
    {
        $this->name = $name;
        $this->alias = $alias;
    }
}
