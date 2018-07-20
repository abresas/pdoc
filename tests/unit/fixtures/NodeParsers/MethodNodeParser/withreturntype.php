<?php
namespace PDoc\tests\fixtures\MethodNodeParser;

class Example1
{
    final protected static function foo(int $x, string $y): array
    {
        return [];
    }
}
