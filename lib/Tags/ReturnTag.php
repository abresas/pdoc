<?php
namespace PDoc\Tags;

use PDoc\Entities\AbstractEntity;

class ReturnTag
{
    public $type;
    public $description;
    public function __construct($type, $description)
    {
        $this->type = $type;
        $this->description = $description;
    }
    public function handledBy(AbstractEntity $e)
    {
        $e->handleReturnTag($this);
    }
}
