<?php
namespace PDoc\Types;

/**
 * Values that can be anything, are not restricted at all.
 */
class AnyType extends AbstractType
{
    public function __toString()
    {
        return 'any';
    }
}
