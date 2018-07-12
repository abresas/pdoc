<?php
namespace PDoc\Types;

/**
 * A type for values that have a different type depending on context.
 *
 * The notation for union types is A|B|C|... Whitespace is allowed between
 * the different types.
 *
 * A value has type UnionType($arrayOfTypes) if its type matches
 * at least one of the types in $arrayOfTypes.
 *
 * For example, a variable is string|bool|null if it is either
 * string, bool OR null.
 *
 * A union type may consist in any number of different types.
 */
class UnionType extends AbstractType
{
    /** @var AbstractType[] $types */
    private $types;
    /**
     * @param AbstractType[] $types The different types consisting this union.
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }
    public function __toString(): string
    {
        return implode(" | ", $this->types);
    }
}
