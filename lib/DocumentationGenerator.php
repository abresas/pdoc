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
    /** @var DirectoryScanner $directoryScanner An object that can scan directories to produce a list of files. */
    protected $directoryScanner;
    public function __construct()
    {
        $this->astParser = new ASTParser();
        $this->rootNodeParser = new RootNodeParser();
        $this->directoryScanner = new DirectoryScanner();
    }
    /**
     * Parse source directory to generate documentation.
     * @param string $sourceDir Path to the project source.
     * @return \PDoc\Entities\PHPNamespace[]
     */
    public function parseDirectory(string $sourceDir): array
    {
        $files = $this->directoryScanner->scan($sourceDir, '/^.*\.php$/');

        $namespaces = [];
        foreach ($files as $filePath) {
            $root = $this->astParser->parseFile($filePath);
            $sourceLoc = new SourceLocation($filePath, $root->lineno);
            $namespace = new PHPNamespace('Global', $sourceLoc, new DocBlock('', '', []));
            $ctx = new ParseContext($filePath, $namespace);
            $symbols = $this->rootNodeParser->parse($root, $ctx);
            $newNamespace = $symbols->namespace;

            if (isset($namespaces[$newNamespace->name])) {
                $namespace = $namespaces[$newNamespace->name];
            } else {
                $namespaces[$newNamespace->name] = $namespace = $newNamespace;
            }

            foreach ($symbols->classes as $class) {
                $namespace->addClass($class);
            }
            foreach ($symbols->functions as $function) {
                $namespace->addFunction($function);
            }
        }
        foreach ($namespaces as $namespace) {
            foreach ($namespace->classes as $class) {
                if (!is_null($class->extends) && isset($namespace->classes[$class->extends])) {
                    $class->parentClass = $namespace->classes[$class->extends];
                }
            }
        }

        return $namespaces;
    }
    public function injectASTParser($astParser): void
    {
        $this->astParser = $astParser;
    }
    public function injectRootNodeParser($parser): void
    {
        $this->rootNodeParser = $parser;
    }
    public function injectDirectoryScanner($dirScanner): void
    {
        $this->directoryScanner = $dirScanner;
    }
}
