<?php
namespace PDoc\Types;

class ArrayType extends AbstractType
{
    public function __construct(AbstractType $t)
    {
        $this->elementType = $t;
    }
    public function __toString(): string
    {
        if ($this->elementType instanceof AnyType) {
            return 'array';
        } else {
            return $this->elementType . '[]';
        }
    }
    public function getLink(): string
    {
        if ($this->elementType instanceof AnyType) {
            return 'array';
        } else {
            return $this->elementType->getLink() . '[]';
        }
    }
}
