<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;

class PHPClass extends AbstractEntity implements \JsonSerializable
{
    public $name;
    public $methods = [];
    public $properties = [];

    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock, $methods = [], $properties = [])
    {
        $this->methods = $methods;
        $this->properties = $properties;
        parent::__construct('class', $name, $loc, $docBlock);
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'methods' => $this->methods,
            'properties' => $this->properties
        ];
    }
}
