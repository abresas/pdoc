<?php
namespace PDoc\NodeParsers;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Classes that process AST nodes to generate AbstractEntity instances.
 */
abstract class AbstractNodeParser implements NodeParser
{
    public function __construct()
    {
    }
    /**
     * @param \ast\Node $node
     * @param ParseContext $ctx
     * @return \PDoc\Entities\AbstractEntity
     */
    public function parse(\ast\Node $node, ParseContext $ctx)
    {
        throw new Exception('Not implemented');
    }
}
