<?php
namespace PDoc\Types;

class VoidType extends AbstractType
{
    public function __toString()
    {
        return 'void';
    }
}
