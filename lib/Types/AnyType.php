<?php
namespace PDoc\Types;

class AnyType extends AbstractType
{
    public function __toString()
    {
        return 'any';
    }
}
