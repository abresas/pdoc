<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\VarTag;
use \PDoc\Types\AnyType;

/**
 * Properties (fields) of a class.
 */
class PHPProperty extends AbstractEntity implements \JsonSerializable
{
    /** @var AbstractType $type */
    public $type;
    /** @var string $visibility "public", "protected", or "private" */
    public $visibility;
    /** @var bool $isStatic */
    public $isStatic;
    /** @var bool $isAbstract */
    public $isAbstract;
    /** @var bool $isFinal */
    public $isFinal;
    /**
     * @param string $name
     * @param SourceLocation $loc
     * @param DocBlock $docBlock
     * @param string $visibility
     * @param bool $isStatic
     * @param bool $isFinal
     * @param bool $isAbstract
     */
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
    /**
     * @param VarTag $tag
     */
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
