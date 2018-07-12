<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPClass;
use \PDoc\Entities\PHPFunction;
use \PDoc\NodeParsers\FileParseResult;
use \PDoc\SourceLocation;

class PHPNamespace extends AbstractEntity implements \JsonSerializable
{
    public $classes = [];
    public $functions = [];
    public function __construct(
        string $name,
        SourceLocation $sourceLoc,
        DocBlock $docBlock,
        $classes = [],
        $functions = []
    ) {
        $this->addClasses($classes);
        $this->addFunctions($functions);
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
        foreach ($classes as $class) {
            $this->addClass($class);
        }
    }
    public function addClass(PHPClass $class)
    {
        $this->classes[$class->name] = $class;
    }
    public function addFunctions(array $functions)
    {
        foreach ($functions as $func) {
            $this->addFunction($func);
        }
    }
    public function addFunction(PHPFunction $function)
    {
        $this->functions[$function->name] = $function;
    }
    public function addSymbols(FileParseResult $symbols)
    {
        foreach ($symbols->classes as $class) {
            $this->addClass($class);
        }
        foreach ($symbols->functions as $function) {
            $this->addFunction($function);
        }
    }
}
