<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocBlock;
use \PDoc\DocCommentParser;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Parse namespace definition, in the first line after opening tag of a PHP file.
 */
class NamespaceNodeParser extends AbstractNodeParser
{
    /** @var DocCommentParser $docCommentParser */
    protected $docCommentParser;

    public function __construct()
    {
        $this->docCommentParser = new DocCommentParser();
    }

    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPNamespace
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $docComment = $node->children['docComment'] ?? '';
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPNamespace($node->children['name']);
    }

    /**
     * @param DocCommentParser $parser
     */
    public function injectDocCommentParser($parser): void
    {
        $this->docCommentParser = $parser;
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
