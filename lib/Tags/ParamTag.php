<?php
namespace PDoc\Tags;

use \PDoc\Entities\AbstractEntity;
use \PDoc\Types\AbstractType;

/**
 * param phpDoc tag
 */
class ParamTag extends AbstractTag
{
    /** @var AbstractType $type */
    public $type;
    /** @var string $variable Parameter name */
    public $variable;
    /** @var string $description */
    public $description;

    /**
     * @param AbstractType $type
     * @param string $variable
     * @param string $description
     */
    public function __construct(AbstractType $type, string $variable, string $description)
    {
        $this->type = $type;
        $this->variable = $variable;
        $this->description = $description;
    }
    /**
     * @param AbstractEntity $e
     */
    public function handledBy(AbstractEntity $e): void
    {
        $e->handleParamTag($this);
    }
}
