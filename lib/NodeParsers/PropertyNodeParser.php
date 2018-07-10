<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPProperty;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class PropertyNodeParser extends AbstractNodeParser
{
    public function parse(Node $node, ParseContext $ctx): PHPProperty
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        // modifiers are set on property declaration level and passed through context instead of node
        // because a declaration may contain multiple properties
        // ie public $foo, $bar;
        if ($ctx->astFlags & \ast\flags\MODIFIER_PUBLIC) {
            $visibility = 'public';
        } elseif ($ctx->astFlags & \ast\flags\MODIFIER_PROTECTED) {
            $visibility = 'protected';
        } elseif ($ctx->astFlags & \ast\flags\MODIFIER_PRIVATE) {
            $visibility = 'private';
        } else {
            $visibility = '?';
            error_log($sourceLoc . ': Unexpected visibility on ' . $node->children['name']);
        }
        $isStatic = $ctx->astFlags & \ast\flags\MODIFIER_STATIC;
        $isAbstract = $ctx->astFlags & \ast\flags\MODIFIER_ABSTRACT;
        $isFinal = $ctx->astFlags & \ast\flags\MODIFIER_FINAL;
        $property = new PHPProperty($node->children['name'], $sourceLoc, $docBlock, $visibility, $isStatic, $isAbstract, $isFinal);
        return $property;
    }
}
