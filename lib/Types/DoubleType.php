<?php
namespace PDoc\Types;

/**
 * Floating-point numbers
 */
class DoubleType extends AbstractType
{
    public function __toString()
    {
        return 'double';
    }
}
