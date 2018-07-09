<?php
namespace PDoc;

use \PDoc\Entities\PHPNamespace;

class ParseContext
{
    public $filePath;
    public $namespace;
    public $aliases;
    public function __construct(string $filePath, PHPNamespace $namespace, array $aliases = [])
    {
        $this->filePath = $filePath;
        $this->namespace = $namespace;
        $this->aliases = $aliases;
    }
    public function addAlias($qualifiedName, $fullName)
    {
        $this->aliases[$qualifiedName] = $fullName;
    }
    public function resolve($name)
    {
        if (isset($this->aliases[$name])) {
            return $this->aliases[$name];
        } else {
            return $name;
        }
    }
}
