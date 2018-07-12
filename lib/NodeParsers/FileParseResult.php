<?php
namespace PDoc\NodeParsers;

use PDoc\Entities\PHPNamespace;

/**
 * The documentation generated from a single file
 */
class FileParseResult
{
    public $namespace;
    public $classes;
    public $functions;
    /**
     * @param PHPNamespace $namespace
     * @param PHPClass[] $classes
     * @param PHPFunction[] $functions
     */
    public function __construct(PHPNamespace $namespace, array $classes, array $functions)
    {
        $this->namespace = $namespace;
        $this->classes = $classes;
        $this->functions = $functions;
    }
}
