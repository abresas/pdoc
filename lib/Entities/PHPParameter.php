<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;

class PHPParameter extends AbstractEntity implements \JsonSerializable
{
    public $type = 'any';
    public $description = '';

    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock)
    {
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
