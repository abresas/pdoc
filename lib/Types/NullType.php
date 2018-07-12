<?php
namespace PDoc\Types;

class NullType extends AbstractType
{
    public function __toString(): string
    {
        return 'null';
    }
}
