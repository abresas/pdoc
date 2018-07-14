<?php
namespace PDoc\Types;

/**
 * Values that implement Callable interface and therefore can be invoked with `()`.
 */
class CallableType extends AbstractType
{
    public function __toString()
    {
        return 'callable';
    }
}
