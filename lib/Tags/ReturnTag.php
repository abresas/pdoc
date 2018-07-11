<?php
namespace PDoc\Tags;

use \PDoc\Entities\AbstractEntity;
use \PDoc\Types\AbstractType;

/**
 * An @return tag.
 *
 * Describes the function return value.
 */
class ReturnTag
{
    /** @var AbstractType $type */
    public $type;
    /** @var string $description */
    public $description;
    /**
     * @param AbstractType $type
     * @param string $description
     */
    public function __construct(AbstractType $type, string $description)
    {
        $this->type = $type;
        $this->description = $description;
    }
    /**
     * @param AbstractEntity $e
     */
    public function handledBy(AbstractEntity $e): void
    {
        $e->handleReturnTag($this);
    }
}
