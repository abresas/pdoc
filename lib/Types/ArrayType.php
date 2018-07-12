<?php
namespace PDoc\Types;

/**
 * A PHP array.
 *
 * May be signified either as "array" in typehints or documentation,
 * in which case the _::elementType_ is _AnyType_,
 * or as _elementType[]_ in the documentation.
 */
class ArrayType extends AbstractType
{
    /** @var AbstractType $elementType */
    private $elementType;
    /**
     * @param AbstractType $t The type of each element. Should be AnyType when not known.
     */
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
    /**
     * @see AbstractType::getLink
     */
    public function getLink(): string
    {
        if ($this->elementType instanceof AnyType) {
            return 'array';
        } else {
            return $this->elementType->getLink() . '[]';
        }
    }
}
