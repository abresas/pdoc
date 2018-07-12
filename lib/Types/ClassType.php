<?php
namespace PDoc\Types;

/**
 * A type for instances of a certain class.
 *
 * This class captures both the fully namespaced name of the class,
 * and the aliased name that was used in the source file it was found.
 *
 * ObjectType is used for objects whose class is unknown, and corresponds
 * to "object" typehint in PHP.
 */
class ClassType extends AbstractType
{
    private $qualifiedName;
    private $fullName;
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
