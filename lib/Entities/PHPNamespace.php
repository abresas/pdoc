<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;

class PHPNamespace extends AbstractEntity implements \JsonSerializable
{
    public $classes;
    public $functions;
    public function __construct(
        string $name,
        SourceLocation $sourceLoc,
        DocBlock $docBlock,
        $classes = [],
        $functions = []
    ) {
        $this->classes = $classes;
        $this->functions = $functions;
        parent::__construct('namespace', $name, $sourceLoc, $docBlock);
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'classes' => $this->classes,
            'functions' => $this->functions,
        ];
    }
    public function addClasses($classes)
    {
        $this->classes = $classes;
    }
    public function addFunctions($functions)
    {
        $this->functions = $functions;
    }
}
