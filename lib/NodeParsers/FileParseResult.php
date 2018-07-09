<?php
namespace PDoc\NodeParsers;

use PDoc\Entities\PHPNamespace;

class FileParseResult
{
    public $namespace;
    public $classes;
    public $functions;
    public function __construct(PHPNamespace $namespace, array $classes, array $functions)
    {
        $this->namespace = $namespace;
        $this->classes = $classes;
        $this->functions = $functions;
    }
}
