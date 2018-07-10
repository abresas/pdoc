<?php
namespace PDoc\Types;

class StringType extends AbstractType
{
    public function __toString()
    {
        return 'string';
    }
}
