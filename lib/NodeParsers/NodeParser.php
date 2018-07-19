<?php
namespace PDoc\NodeParsers;

interface NodeParser
{
    public function parse(\ast\Node $node, \Pdoc\ParseContext $ctx);
}
