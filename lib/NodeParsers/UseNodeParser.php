<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ParseContext;
use \PDoc\Entities\UseAlias;

class UseNodeParser extends AbstractNodeParser
{
    public function parse(Node $node, ParseContext $ctx)
    {
        $name = '\\' . $node->children['name'];
        $alias = $node->children['alias'] ?? substr($name, strrpos($name, '\\') + 1);
        return new UseAlias($name, $alias);
    }
}
