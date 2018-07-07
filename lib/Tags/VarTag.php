<?php
namespace PDoc\Tags;

use PDoc\Entities\AbstractEntity;

class VarTag
{
    public $type;
    public $variable;
    public $description;
    public function __construct($type, $variable, $description)
    {
        $this->type = $type;
        $this->variable = $variable;
        $this->description = $description;
    }
    public function handledBy(AbstractEntity $e)
    {
        $e->handleVarTag($this);
    }
}
