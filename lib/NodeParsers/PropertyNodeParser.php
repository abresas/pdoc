<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocCommentParser;
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
    private $docCommentParser;
    public function __construct()
    {
        $this->docCommentParser = new DocCommentParser();
    }

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

        $visibility = $this->parseVisibility($ctx->astFlags, $propName, $sourceLoc);
        $isStatic = $ctx->astFlags & \ast\flags\MODIFIER_STATIC;
        $isAbstract = $ctx->astFlags & \ast\flags\MODIFIER_ABSTRACT;
        $isFinal = $ctx->astFlags & \ast\flags\MODIFIER_FINAL;

        return new PHPProperty($propName, $sourceLoc, $docBlock, $visibility, $isStatic, $isFinal, $isAbstract);
    }

    /**
     * @TODO This is similar with MethodNodeParser::parseVisibility
     * @param int $astFlags
     * @param string $propName Name of property currently being parsed.
     * @return string "public", "protected" or "private"
     */
    private function parseVisibility(int $astFlags, string $propName, SourceLocation $sourceLoc): string
    {
        $flagMap = [
            \ast\flags\MODIFIER_PUBLIC => 'public',
            \ast\flags\MODIFIER_PROTECTED => 'protected',
            \ast\flags\MODIFIER_PRIVATE => 'private'
        ];
        // modifiers are set on property statement level and passed through context instead of node
        // because a statement may contain multiple properties
        // ie public $foo, $bar;
        foreach ($flagMap as $flag => $visibility) {
            if ($astFlags & $flag) {
                return $visibility;
            }
        }
        throw new \Exception($sourceLoc . ': Unexpected visibility on ' . $propName);
    }

    /**
     * Parse the doc comment found above the currently parsed node.
     * @param string $docComment The text of the phpDoc comment.
     * @param ParseContext $ctx The state of the parser when parsing this node.
     * @param SourceLocation $sourceLoc The file and line where the current node was found.
     */
    private function parseDocComment(string $docComment, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        return $this->docCommentParser->parse($docComment, $ctx, $sourceLoc);
    }
}
