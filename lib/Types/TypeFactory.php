<?php
namespace PDoc\Types;

use \PDoc\ParseContext;

/**
 * Construct instances of AbstractType from various formats/objects.
 * @TODO: rename into TypeStringParser ?
 */
class TypeFactory
{
    public function fromDocumentationString(string $typeStr, ParseContext $ctx): AbstractType
    {
        if (strpos($typeStr, '|') !== false) {
            $subTypes = array_map(function ($s) {
                return $this->fromDocumentationString($s, $ctx);
            }, explode('|', $typeStr));
            return new UnionType($subTypes);
        } elseif (substr($typeStr, -2) === '[]') {
            $subType = substr($typeStr, 0, -2);
            return new ArrayType($this->fromDocumentationString($subType, $ctx));
        } elseif ($typeStr === 'array') {
            return new ArrayType(new AnyType());
        } elseif ($typeStr === 'callable') {
            return new ArrayType(new CallableType());
        } elseif ($typeStr === 'void') {
            return new VoidType();
        } elseif ($typeStr === 'bool') {
            return new BoolType();
        } elseif ($typeStr === 'long') {
            return new LongType();
        } elseif ($typeStr === 'double') {
            return new DoubleType();
        } elseif ($typeStr === 'string') {
            return new StringType();
        } elseif ($typeStr === 'object') {
            return new ObjectType();
        } else {
            // @TODO not always classtype, may be unknown format
            return new ClassType($typeStr, $ctx->resolve($typeStr));
        }
    }
}
