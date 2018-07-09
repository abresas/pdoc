<?php
namespace PDoc;

use \PDoc\FileSystem\DirectoryScanner;
use \PDoc\NodeParsers\RootNodeParser;

class DocumentationGenerator
{
    protected $astParser;
    protected $rootNodeParser;
    protected $classTemplate;
    protected $directoryScanner;
    protected $fileWriter;
    public function __construct()
    {
        $this->astParser = new ASTParser();
        $this->rootNodeParser = new RootNodeParser();
        $this->directoryScanner = new DirectoryScanner();
    }
    public function parseDirectory($sourceDir)
    {
        $files = $this->directoryScanner->scan($sourceDir, '/^.*\.php$/');

        $namespaces = [];
        foreach ($files as $filePath) {
            $root = $this->astParser->parseFile($filePath);
            $symbols = $this->rootNodeParser->parse($root, $filePath);
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

        return $namespaces;
    }
    public function injectASTParser($astParser)
    {
        $this->astParser = $astParser;
    }
    public function injectRootNodeParser($parser)
    {
        $this->rootNodeParser = $parser;
    }
    public function injectDirectoryScanner($dirScanner)
    {
        $this->directoryScanner = $dirScanner;
    }
}
