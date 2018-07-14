<?php
namespace PDoc\Types;

/**
 * Long integers.
 */
class LongType extends AbstractType
{
    public function __toString()
    {
        return 'long';
    }
}
