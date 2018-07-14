<?php
namespace PDoc\Types;

/**
 * Values that implement Iterable inerface.
 */
class IterableType extends AbstractType
{
    public function __toString()
    {
        return 'iterable';
    }
}
