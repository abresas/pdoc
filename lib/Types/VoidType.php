<?php
namespace PDoc\Types;

/**
 * Return type for functions that do not return anything.
 */
class VoidType extends AbstractType
{
    public function __toString()
    {
        return 'void';
    }
}
