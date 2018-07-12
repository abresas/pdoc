<?php
namespace PDoc;

use \PDoc\Entities\PHPNamespace;
use \PDoc\Entities\UseAlias;

/**
 * Shared parser memory for parsers operating on the same source code file.
 *
 * Handles aliases and keeping track of current filename, namespace and AST flags that operate on child nodes.
 */
class ParseContext
{
    public $filePath;
    public $namespace;
    public $aliases = [];
    public $astFlags;
    /**
     * @param string $filePath Path to file currently being parsed.
     * @param PHPNamespace $namespace The namespace this file is defined on (according first line in the PHP file).
     * @param UseAlias[] $aliases Aliases being used.
     * @param int $astFlags AST flags that are switched on for this context.
     */
    public function __construct(string $filePath, PHPNamespace $namespace, array $aliases = [], $astFlags = 0)
    {
        $this->filePath = $filePath;
        $this->namespace = $namespace;
        $this->addAliases($aliases);
        $this->astFlags = $astFlags;
    }
    /**
     * Add an alias that was used in the source code.
     * @param UseAlias $alias The alias to add.
     */
    public function addAlias(UseAlias $alias): void
    {
        $this->aliases[$alias->alias] = $alias;
    }
    /**
     * Add aliases that was used in the source code.
     * @param UseAlias[] $aliases The aliases to add.
     */
    public function addAliases(array $aliases): void
    {
        foreach ($aliases as $alias) {
            $this->addAlias($alias);
        }
    }
    /**
     * Resolve a (possibly) qualified class name to full name (including namespace).
     * @param string $name The sort or qualified class name that was found in source code.
     * @return string The full namespaced name for the class.
     */
    public function resolve(string $name): string
    {
        if (isset($this->aliases[$name])) {
            return $this->aliases[$name]->name;
        } elseif ($name[0] !== '\\') {
            return '\\' . $this->namespace->name . '\\' . $name;
        } else {
            return $name;
        }
    }
}
