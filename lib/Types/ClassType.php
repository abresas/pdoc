<?php
namespace PDoc\Types;

class ClassType extends AbstractType
{
    public function __construct($qualifiedName, $fullName)
    {
        $this->qualifiedName = $qualifiedName;
        $this->fullName = $fullName;
    }
    public function __toString()
    {
        return $this->qualifiedNamu;
    }
    public function getURL(string $base): string
    {
        return $base . DIRECTORY_SEPARATOR . str_replace('\\', '.', $this->fullName) . '.html';
    }
}
