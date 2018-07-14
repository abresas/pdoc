<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPProperty;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Parse class properties.
 *
 * @see PropertyDeclarationNodeParser
 */
class PropertyNodeParser extends AbstractNodeParser
{
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPProperty
     */
    public function parse(Node $node, ParseContext $ctx): PHPProperty
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $propName = $node->children['name'];

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        $visibility = $this->parseVisibility($ctx->astFlags, $propName);
        $isStatic = $ctx->astFlags & \ast\flags\MODIFIER_STATIC;
        $isAbstract = $ctx->astFlags & \ast\flags\MODIFIER_ABSTRACT;
        $isFinal = $ctx->astFlags & \ast\flags\MODIFIER_FINAL;

        return new PHPProperty($propName, $sourceLoc, $docBlock, $visibility, $isStatic, $isAbstract, $isFinal);
    }
    /**
     * @TODO This is similar with MethodNodeParser::parseVisibility
     * @param int $astFlags
     * @param string $propName Name of property currently being parsed.
     * @return string "public", "protected" or "private"
     */
    public function parseVisibility(int $astFlags, string $propName): string
    {
        // modifiers are set on property statement level and passed through context instead of node
        // because a statement may contain multiple properties
        // ie public $foo, $bar;
        if ($astFlags & \ast\flags\MODIFIER_PUBLIC) {
            return 'public';
        } elseif ($astFlags & \ast\flags\MODIFIER_PROTECTED) {
            return 'protected';
        } elseif ($astFlags & \ast\flags\MODIFIER_PRIVATE) {
            return 'private';
        } else {
            throw new Exception($sourceLoc . ': Unexpected visibility on ' . $propName);
        }
    }
}
