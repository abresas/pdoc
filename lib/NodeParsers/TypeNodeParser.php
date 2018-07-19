<?php
namespace PDoc\NodeParsers;

use ast\Node;

use PDoc\ParseContext;
use PDoc\Types\AbstractType;
use PDoc\Types\AnyType;
use PDoc\Types\ArrayType;
use PDoc\Types\BoolType;
use PDoc\Types\CallableType;
use PDoc\Types\ClassType;
use PDoc\Types\DoubleType;
use PDoc\Types\IterableType;
use PDoc\Types\LongType;
use PDoc\Types\NullType;
use PDoc\Types\ObjectType;
use PDoc\Types\StringType;
use PDoc\Types\UnionType;
use PDoc\Types\VoidType;

/**
 * Parse an AST node that represents a typehint in function or method declarations.
 */
class TypeNodeParser
{
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return AbstractType
     */
    public function parse(Node $node, ParseContext $ctx): AbstractType
    {
        if ($node->kind === \ast\AST_NAME) {
            return new ClassType($node->children['name'], $ctx->resolve($node->children['name']));
        } elseif ($node->kind === \ast\AST_NULLABLE_TYPE) {
            return new UnionType([ $this->parse($node->children['type'], $ctx), new NullType() ]);
        } elseif ($node->kind === \ast\AST_TYPE) {
            if ($node->flags === \ast\flags\TYPE_ARRAY) {
                return new ArrayType(new AnyType());
            } elseif ($node->flags === \ast\flags\TYPE_CALLABLE) {
                return new CallableType();
            } elseif ($node->flags === \ast\flags\TYPE_VOID) {
                return new VoidType();
            } elseif ($node->flags === \ast\flags\TYPE_BOOL) {
                return new BoolType();
            } elseif ($node->flags === \ast\flags\TYPE_LONG) {
                return new LongType();
            } elseif ($node->flags === \ast\flags\TYPE_DOUBLE) {
                return new DoubleType();
            } elseif ($node->flags === \ast\flags\TYPE_STRING) {
                return new StringType();
            } elseif ($node->flags === \ast\flags\TYPE_ITERABLE) {
                return new IterableType();
            } elseif ($node->flags === \ast\flags\TYPE_OBJECT) {
                return new ObjectType();
            } else {
                throw new \Exception($ctx->filePath . ':' . $node->lineno . ': Unexpected AST_TYPE: ' . $node->flags);
            }
        } else {
            throw new \Exception($ctx->filePath . ':' . $node->lineno . ': Unexpected type node kind: ' . $node->kind);
        }
    }
}
