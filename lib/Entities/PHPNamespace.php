<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPClass;
use \PDoc\Entities\PHPFunction;
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
    public function addClasses(array $classes)
    {
        $this->classes = $classes;
    }
    public function addClass(PHPClass $class)
    {
        $this->classes[] = $class;
    }
    public function addFunctions(array $functions)
    {
        $this->functions = $functions;
    }
    public function addFunction(PHPFunction $function)
    {
        $this->functions[] = $function;
    }
}
