<?php
namespace PDoc\Types;

/**
 * Values that are true or false.
 */
class BoolType extends AbstractType
{
    public function __toString()
    {
        return 'bool';
    }
}
