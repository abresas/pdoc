<?php
namespace PDoc\Types;

/**
 * Values that are lists of characters.
 */
class StringType extends AbstractType
{
    public function __toString()
    {
        return 'string';
    }
}
