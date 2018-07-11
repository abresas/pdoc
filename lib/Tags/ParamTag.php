<?php
namespace PDoc\Tags;

use \PDoc\Entities\AbstractEntity;
use \PDoc\Types\AbstractType;

class ParamTag
{
    public $type;
    public $variable;
    public $description;
    public function __construct(AbstractType $type, string $variable, string $description)
    {
        $this->type = $type;
        $this->variable = $variable;
        $this->description = $description;
    }
    public function handledBy(AbstractEntity $e)
    {
        $e->handleParamTag($this);
    }
}
