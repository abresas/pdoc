<?php
namespace PDoc\Tags;

use \PDoc\Entities\AbstractEntity;
use \PDoc\Types\AbstractType;

/**
 * @var tag
 *
 * Format: @var _type_ _$variableName_ _description..._
 */
class VarTag
{
    /** @var AbstractType $type */
    public $type;
    /** @var string $variable */
    public $variable;
    /** @var string $description */
    public $description;
    public function __construct(AbstractType $type, string $variable, string $description = '')
    {
        $this->type = $type;
        $this->variable = $variable;
        $this->description = $description;
    }
    /**
     * @param AbstractEntity $e
     * @return void
     */
    public function handledBy(AbstractEntity $e): void
    {
        $e->handleVarTag($this);
    }
}
