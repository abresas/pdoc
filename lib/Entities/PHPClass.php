<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Types\VoidType;

class PHPClass extends AbstractEntity implements \JsonSerializable
{
    public $methods = [];
    public $properties = [];
    public $constructor;

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
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'methods' => $this->methods,
            'properties' => $this->properties
        ];
    }
}
