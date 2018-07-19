<?php
namespace PDoc;

use \PDoc\NodeParsers\FileParseResult;

class Documentation implements \IteratorAggregate
{
    private $namespaces = [];
    public function addSymbols(FileParseResult $symbols)
    {
        $newNamespace = $symbols->namespace;
        $namespace = $this->namespaces[$newNamespace->name] ?? $newNamespace;
        $this->namespaces[$namespace->name] = $namespace;
        $namespace->addSymbols($symbols);
    }
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }
    public function getIterator()
    {
        $namespaces = [];
        foreach ($this->namespaces as $namespace) {
            foreach ($namespace->classes as $class) {
                if (is_null($class->extends)) {
                    continue;
                }
                $parentNameParts = explode("\\", $class->extends);
                $parentNamespace = substr(join("\\", array_slice($parentNameParts, 0, -1)), 1);
                $parentName = $parentNameParts[count($parentNameParts) - 1];

                if (!is_null($class->extends) && isset($namespaces[$parentNamespace]->classes[$parentName])) {
                    $class->parentClass = $namespaces[$parentNamespace]->classes[$parentName];
                }
            }
            $namespaces[] = $namespace;
        }

        uasort($namespaces, function ($n1, $n2) {
            return $n1->name < $n2->name ? -1 : 1;
        });
        return new \ArrayIterator($namespaces);
    }
}
