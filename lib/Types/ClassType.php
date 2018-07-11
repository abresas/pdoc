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
        return $this->qualifiedName;
    }
    public function getURL(): string
    {
        return urlencode(str_replace('\\', '.', substr($this->fullName, 1))) . '.html';
    }
    public function getLink(): string
    {
        return '<a href="' . $this->getURL() . '">' . htmlspecialchars($this->qualifiedName) . '</a>';
    }
}
