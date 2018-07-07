<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\VarTag;

class PHPProperty extends AbstractEntity implements \JsonSerializable
{
    public $type;
    public $description = '';
    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock)
    {
        parent::__construct('property', $name, $loc, $docBlock);
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description
        ];
    }
    public function handleVarTag(VarTag $tag)
    {
        $this->type = $tag->type;
        $this->description = $tag->description;
    }
}
