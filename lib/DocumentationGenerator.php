<?php
namespace PDoc;

use \PDoc\Entities\PHPNamespace;
use \PDoc\FileSystem\DirectoryScanner;
use \PDoc\NodeParsers\RootNodeParser;

/**
 * Main class. Parse a directory and generate documentation.
 */
class DocumentationGenerator
{
    /** @var ASTParser $astParser Parses PHP AST. */
    protected $astParser;
    /** @var RootNodeParser $rootNodeParser Parses the root AST node of a .php file. */
    protected $rootNodeParser;

    public function __construct()
    {
        $this->astParser = new ASTParser();
        $this->rootNodeParser = new RootNodeParser();
    }

    public function parseFiles(iterable $files): Documentation
    {
        $documentation = new Documentation();
        foreach ($files as $filePath) {
            $root = $this->astParser->parseFile($filePath);
            $sourceLoc = new SourceLocation($filePath, $root->lineno);
            $namespace = new PHPNamespace('Global');
            $ctx = new ParseContext($filePath, $namespace);
            $symbols = $this->rootNodeParser->parse($root, $ctx);
            $documentation->addSymbols($symbols);
        }
        return $documentation;
    }

    public function injectASTParser($astParser): void
    {
        $this->astParser = $astParser;
    }

    public function injectRootNodeParser($parser): void
    {
        $this->rootNodeParser = $parser;
    }
}
