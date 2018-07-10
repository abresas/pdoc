<?php
namespace PDoc\Types;

class CallableType extends AbstractType
{
    public function __toString()
    {
        return 'callable';
    }
}
