<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Types\AbstractType;

/**
 * A parameter to a method or function.
 *
 * Each parameter can have a type (defined in typehint or doc comment)
 * and a description. There is no long and short description for parameters
 * as we support parameter description only via _param_ tags
 */
class PHPParameter extends AbstractEntity implements \JsonSerializable
{
    /** @var \PDoc\Types\AbstractType $type The type of this parameter. */
    public $type;
    /** @var string $description A description of this parameter. */
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
