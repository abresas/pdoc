<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocCommentParser;
use \PDoc\Entities\PHPParameter;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;
use \PDoc\Types\AnyType;

/**
 * Parse parameters to functions or methods.
 *
 * Each parameter has a name and optionally a typehint, and may have
 * a phpDoc comment if each parameter is placed in its own line.
 */
class ParameterNodeParser extends AbstractNodeParser
{
    /** @var TypeNodeParser $typeNodeParser */
    private $typeNodeParser;
    /** @var DocCommentParser $docCommentParser */
    protected $docCommentParser;

    public function __construct()
    {
        parent::__construct();
        $this->typeNodeParser = new TypeNodeParser();
        $this->docCommentParser = new DocCommentParser();
    }

    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPParameter
     */
    public function parse(Node $node, ParseContext $ctx): PHPParameter
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        if (!empty($node->children['type'])) {
            $type = $this->typeNodeParser->parse($node->children['type'], $ctx);
        } else {
            $type = new AnyType();
        }
        return new PHPParameter($node->children['name'], $type, $sourceLoc, $docBlock);
    }

    public function injectTypeNodeParser($parser): void
    {
        $this->typeNodeParser = $parser;
    }

    /**
     * Parse the doc comment found above the currently parsed node.
     * @param string $docComment The text of the phpDoc comment.
     * @param ParseContext $ctx The state of the parser when parsing this node.
     * @param SourceLocation $sourceLoc The file and line where the current node was found.
     */
    protected function parseDocComment(string $docComment, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        return $this->docCommentParser->parse($docComment, $ctx, $sourceLoc);
    }
}
