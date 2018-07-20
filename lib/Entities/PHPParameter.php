<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Types\AbstractType;

/**
 * A parameter to a method or function.
 *
 * Each parameter has type (Any if not defined).
 * They can be documented with @param tag on the function phpDoc comment.
 * We do not support doc comments on each parameter separately.
 */
class PHPParameter extends AbstractEntity implements \JsonSerializable
{
    /** @var \PDoc\Types\AbstractType $type The type of this parameter. */
    public $type;
    /** @var string $description */
    public $description;

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
