<?php
namespace PDoc\Types;

class AbstractType
{
    public function getURL(): string
    {
        return $this->__toString();
    }
    public function getLink(): string
    {
        return $this->__toString();
    }
}
