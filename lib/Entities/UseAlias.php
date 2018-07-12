<?php
namespace PDoc\Entities;

/**
 * A "use _namespaced-class_" declaration.
 *
 * The declaration may end with "as _alias_" to specify
 * a different name for the class. Otherwise the class name
 * (without namespace) can be used for referencing the class.
 */
class UseAlias
{
    /** Name of referenced class. */
    public $name;
    /** Alias used within a file for this class. */
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
