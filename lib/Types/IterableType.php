<?php
namespace PDoc\Types;

class IterableType extends AbstractType
{
    public function __toString()
    {
        return 'iterable';
    }
}
