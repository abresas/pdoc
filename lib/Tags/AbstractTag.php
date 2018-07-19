<?php
namespace PDoc\Tags;

use \PDoc\Entities\AbstractEntity;

abstract class AbstractTag
{
    public function handledBy(AbstractEntity $e): void
    {
    }
}
