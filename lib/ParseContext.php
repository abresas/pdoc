<?php
namespace PDoc;

use \PDoc\Entities\PHPNamespace;
use \PDoc\Entities\UseAlias;

class ParseContext
{
    public $filePath;
    public $namespace;
    public $aliases = [];
    public $astFlags;
    public function __construct(string $filePath, PHPNamespace $namespace, array $aliases = [], $astFlags = 0)
    {
        $this->filePath = $filePath;
        $this->namespace = $namespace;
        $this->addAliases($aliases);
        $this->astFlags = $astFlags;
    }
    public function addAlias(UseAlias $alias)
    {
        $this->aliases[$alias->alias] = $alias;
    }
    public function addAliases(array $aliases)
    {
        foreach ($aliases as $alias) {
            $this->addAlias($alias);
        }
    }
    public function resolve(string $name): string
    {
        if (isset($this->aliases[$name])) {
            return $this->aliases[$name]->name;
        } else if ($name[0] !== '\\') {
            return '\\' . $this->namespace->name . '\\' . $name;
        } else {
            return $name;
        }
    }
}
