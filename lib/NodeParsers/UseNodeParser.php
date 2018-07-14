<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ParseContext;
use \PDoc\Entities\UseAlias;

/**
 * Parse "use namespace [as alias]" statements.
 */
class UseNodeParser extends AbstractNodeParser
{
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return UseAlias
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $name = '\\' . $node->children['name'];
        $alias = $node->children['alias'] ?? substr($name, strrpos($name, '\\') + 1);
        return new UseAlias($name, $alias);
    }
}
