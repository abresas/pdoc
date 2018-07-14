<?php
namespace PDoc\Entities;

/**
 * Define aliases for namespaced classes.
 *
 * `Use` statements have two formats.
 *
 * With `use \namespace\of\className` the class can be referenced using simply the
 * class name.
 *
 * Otherwise, `use \namespace\of\className as aliasName` can be used to explicitly
 * set the desired alias for the class in the current file.
 */
class UseAlias
{
    /** Name of referenced class. */
    public $name;
    /** Alias used within a file for this class. Is same as last part of ::name if not set explicitly. */
    public $alias;
    /**
     * @param string $name The name of the referenced class.
     * @param string $alias The alias used within a file for this class.
     */
    public function __construct(string $name, string $alias)
    {
        $this->name = $name;
        $this->alias = $alias;
    }
}
