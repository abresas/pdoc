<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Types\VoidType;

/**
 * PHP Language Classes.
 *
 * A class has its own documentation, as well as methods and properties.
 * The constructor is treated separately from methods.
 */
class PHPClass extends AbstractEntity implements \JsonSerializable
{
    /** @var PHPMethod[] $methods The methods of the class (excluding constructor). */
    public $methods = [];
    /** @var PHPProperty[] $properties The properties of the class. */
    public $properties = [];
    /** @var PHPMethod|null $constructor The constructor, an empty one assumed if not defined. */
    public $constructor;
    /** @var string|null $extends Name of parent class, if applicable. */
    public $extends;
    /** @var PHPClass|null $parentClass Parent class object, if applicable. */
    public $parentClass = null;

    /**
     * @param string $name The name of the class.
     * @param SourceLocation $loc The path and line where it was defined.
     * @param DocBlock $docBlock Documentation attributes.
     * @param PHPMethod[] $methods Array of methods defined in the class.
     * @param PHPProperty[] $properties Array of properties defined in the class.
     */
    public function __construct(string $name, ?string $extends, SourceLocation $loc, DocBlock $docBlock, $methods = [], $properties = [])
    {
        $this->extends = $extends;
        $this->constructor = null;

        foreach ($properties as $property) {
            $this->properties[$property->name] = $property;
        }
        foreach ($methods as $method) {
            if ($method->name === '__construct') {
                $this->constructor = $method;
            } else {
                $this->methods[$method->name] = $method;
            }
        }
        parent::__construct('class', $name, $loc, $docBlock);
    }
    public function setParentClass(PHPClass $parentClass): void
    {
        assert($parentClass->name === $this->extends);
        $this->parentClass = $parentClass;
    }
    public function getConstructor()
    {
        if (!is_null($this->constructor)) {
            return $this->constructor;
        } elseif (!is_null($this->parentClass)) {
            return $this->parentClass->getConstructor();
        } else {
            return new PHPMethod('__construct', $this->sourceLocation, new DocBlock('', '', []), new VoidType(), 'public', false, false, false);
        }
    }
    public function getAllMethods(): array
    {
        $methods = array_merge($this->methods, $this->parentClass ? $this->parentClass->getAllMethods() : []);
        ksort($methods);
        return $methods;
    }
    public function getAllProperties(): array
    {
        $props = array_merge($this->properties, $this->parentClass ? $this->parentClass->getAllProperties() : []);
        ksort($props);
        return $props;
    }
    /**
     * Implementation of JsonSerializable interface.
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'extends' => $this->extends,
            'methods' => $this->methods,
            'properties' => $this->properties
        ];
    }
}
