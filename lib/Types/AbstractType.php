<?php
namespace PDoc\Types;

/**
 * The parent class for all types.
 *
 * A type may be found as a source code typehint, or in the documentation
 * in _param_, _var_ or _return_ tags.
 */
class AbstractType
{
    /**
     * @return null
     */
    public function __toString()
    {
        throw new Exception('Not implemented');
    }
    /**
     * Get a link for documentation to this type, if possible.
     * Otherwise just return a human-friendly string.
     */
    public function getLink(): string
    {
        return $this->__toString();
    }
}
