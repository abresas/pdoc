<?php
namespace PDoc\NodeParsers;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

abstract class AbstractNodeParser
{
    protected $filePath;
    protected $astFinder;
    protected $docCommentParser;
    public function __construct()
    {
        $this->astFinder = new ASTFinder();
        $this->docCommentParser = new DocCommentParser();
    }
    public function parseDocComment(string $docComment, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        return $this->docCommentParser->parse($docComment, $ctx, $sourceLoc);
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
