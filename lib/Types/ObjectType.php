<?php
namespace PDoc\Types;

/**
 * Objects that are instances of ANY class.
 */
class ObjectType extends AbstractType
{
    public function __toString()
    {
        return 'object';
    }
}
