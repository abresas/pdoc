<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\VarTag;
use \PDoc\Types\AnyType;

class PHPProperty extends AbstractEntity implements \JsonSerializable
{
    public $type = null;
    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock, string $visibility, bool $isStatic, bool $isFinal, bool $isAbstract)
    {
        parent::__construct('property', $name, $loc, $docBlock);
        $this->type = new AnyType();
        $this->visibility = $visibility;
        $this->isStatic = $isStatic;
        $this->isAbstract = $isAbstract;
        $this->isFinal = $isFinal;
        $this->setDocBlock($docBlock);
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'shortDescription' => $this->shortDescription,
            'longDescription' => $this->longDescription
        ];
    }
    public function handleVarTag(VarTag $tag): void
    {
        if ($tag->variable !== null && $tag->variable !== $this->name) {
            error_log($this->sourceLocation . ': @var tag variable name "' . $tag->variable . '" does not match the subsequent property.');
            return;
        }
        $this->type = $tag->type;
        $this->shortDescription = $tag->description;
    }
}
