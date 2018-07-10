<?php
namespace PDoc\Types;

class BoolType extends AbstractType
{
    public function __toString()
    {
        return 'bool';
    }
}
