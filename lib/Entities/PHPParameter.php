<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Types\AbstractType;

class PHPParameter extends AbstractEntity implements \JsonSerializable
{
    public $type;
    public $description = '';

    public function __construct(string $name, AbstractType $type, SourceLocation $loc, DocBlock $docBlock)
    {
        $this->type = $type;
        parent::__construct('parameter', $name, $loc, $docBlock);
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description
        ];
    }
}
