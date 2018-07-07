<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;

abstract class AbstractNodeParser
{
    protected $filePath;
    protected $astFinder;
    protected $docCommentParser;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->astFinder = new ASTFinder();
        $this->docCommentParser = new DocCommentParser();
    }
    public function parse(Node $node)
    {
        throw new \Exception('::parse not implemented on ' . get_class($this));
    }
    public function parseDocComment(string $docComment)
    {
        return $this->docCommentParser->parse($docComment);
    }
    public function injectASTFinder($astFinder)
    {
        $this->astFinder = $astFinder;
    }
    public function injectDocCommentParser($docCommentParser)
    {
        $this->docCommentParser = $docCommentParser;
    }
}
