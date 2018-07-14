<?php
namespace PDoc\Types;

/**
 * Values that are always null.
 */
class NullType extends AbstractType
{
    public function __toString(): string
    {
        return 'null';
    }
}
