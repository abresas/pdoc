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
    /** @var PHPMethod $constructor The constructor, an empty one assumed if not defined. */
    public $constructor;

    /**
     * @param string $name The name of the class.
     * @param SourceLocation $loc The path and line where it was defined.
     * @param DocBlock $docBlock Documentation attributes.
     * @param PHPMethod[] $methods Array of methods defined in the class.
     * @param PHPProperty[] $properties Array of properties defined in the class.
     */
    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock, $methods = [], $properties = [])
    {
        $this->properties = $properties;
        // initialize constructor in case it is not defined
        $this->constructor = new PHPMethod('__construct', $loc, new DocBlock('', '', []), new VoidType(), 'public', false, false, false);

        foreach ($methods as $method) {
            if ($method->name === '__construct') {
                $this->constructor = $method;
            } else {
                $this->methods[] = $method;
            }
        }
        parent::__construct('class', $name, $loc, $docBlock);
    }
    /**
     * Implementation of JsonSerializable interface.
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'methods' => $this->methods,
            'properties' => $this->properties
        ];
    }
}
