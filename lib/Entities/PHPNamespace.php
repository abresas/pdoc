<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPClass;
use \PDoc\Entities\PHPFunction;
use \PDoc\NodeParsers\FileParseResult;
use \PDoc\SourceLocation;

/**
 * Declaration of current namespace (first line in PHP file).
 *
 * Also holds all classes and functions that were defined in this namespace.
 */
class PHPNamespace implements \JsonSerializable
{
    /** @var PHPClass[] $classes The classes that were defined in this namespace. */
    public $classes = [];
    /** @var PHPFunction[] $functions The functions that were defined in this namespace. */
    public $functions = [];
    /** @var string $name The namespace name */
    public $name;
    /**
     * @param string $name The name of the namespace.
     * @param PHPClass[] $classes Classes defined in this namespace.
     * @param PHPFunction[] $functions Functions defined in this namespace.
     */
    public function __construct(
        string $name,
        $classes = [],
        $functions = []
    ) {
        $this->name = $name;
        $this->addClasses($classes);
        $this->addFunctions($functions);
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'classes' => $this->classes,
            'functions' => $this->functions,
        ];
    }
    /**
     * Add classes to the list of classes in this namespace.
     */
    public function addClasses(array $classes): void
    {
        foreach ($classes as $class) {
            $this->addClass($class);
        }
    }
    /**
     * Add a class to the list of classes in this namespace.
     * @param PHPClass $class The class to add.
     */
    public function addClass(PHPClass $class): void
    {
        $this->classes[$class->name] = $class;
    }
    /**
     * Add functions to the list of functions in this namespace.
     * @param PHPFunction[] $functions The functions to add.
     */
    public function addFunctions(array $functions): void
    {
        foreach ($functions as $func) {
            $this->addFunction($func);
        }
    }
    /**
     * Add a function to the list of functions in this namespace.
     * @param PHPFunction $function The function to add.
     */
    public function addFunction(PHPFunction $function): void
    {
        $this->functions[$function->name] = $function;
    }
    /**
     * Add symbols (classes and functions) defined in a file to this namespace.
     */
    public function addSymbols(FileParseResult $symbols): void
    {
        foreach ($symbols->classes as $class) {
            $this->addClass($class);
        }
        foreach ($symbols->functions as $function) {
            $this->addFunction($function);
        }
    }
    /**
     * Get a URL-friendly name for this namespace, for links.
     */
    public function getURI(): string
    {
        return urlencode(str_replace("\\", "", $this->name));
    }
}
