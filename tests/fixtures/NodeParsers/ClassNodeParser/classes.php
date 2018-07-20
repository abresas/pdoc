<?php

class TheParent
{
    public $x, $x2;
    protected $y;
    private $z;
    public static function foo(): string
    {
    }
    protected function bar()
    {
    }
}

class Child extends TheParent
{
    public $x, $x2;
    protected $y;
    private $z;
    public static function foo(): string
    {
    }
    protected function bar()
    {
    }
}

