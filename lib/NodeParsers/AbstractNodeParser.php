<?php
namespace PDoc\NodeParsers;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Classes that process AST nodes to generate AbstractEntity instances.
 */
abstract class AbstractNodeParser
{
    /** @var string $filePath */
    protected $filePath;
    /** @var ASTFinder $astFinder */
    protected $astFinder;
    /** @var DocCommentParser $docCommentParser */
    protected $docCommentParser;
    public function __construct()
    {
        $this->astFinder = new ASTFinder();
        $this->docCommentParser = new DocCommentParser();
    }
    /**
     * @param \ast\Node $node
     * @param ParseContext $ctx
     * @return \PDoc\Entities\AbstractEntity
     */
    public function parse(\ast\Node $node, ParseContext $ctx)
    {
        throw new Exception('Not implemented');
    }
    /**
     * Parse the doc comment found above the currently parsed node.
     * @param string $docComment The text of the phpDoc comment.
     * @param ParseContext $ctx The state of the parser when parsing this node.
     * @param SourceLocation $sourceLoc The file and line where the current node was found.
     */
    public function parseDocComment(string $docComment, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        return $this->docCommentParser->parse($docComment, $ctx, $sourceLoc);
    }
    public function injectASTFinder($astFinder): void
    {
        $this->astFinder = $astFinder;
    }
    public function injectDocCommentParser($docCommentParser): void
    {
        $this->docCommentParser = $docCommentParser;
    }
}
