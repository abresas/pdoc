<?php
namespace PDoc\Types;

class AbstractType
{
    public function getURL(string $baseUrl): string
    {
        return $this->__toString();
    }
}
