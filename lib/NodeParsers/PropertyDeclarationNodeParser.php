<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class PropertyDeclarationNodeParser extends AbstractNodeParser
{
    private $propertyNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->propertyNodeParser = new PropertyNodeParser();
    }
    public function parse(Node $node, ParseContext $ctx)
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $declCtx = new ParseContext($ctx->filePath, $ctx->namespace, $ctx->aliases, $node->flags);
        $properties = $this->astFinder->parseWith($node, $declCtx, \ast\AST_PROP_ELEM, $this->propertyNodeParser);
        return $properties;
    }
    public function injectPropertyNodeParser($parser)
    {
        $this->propertyNodeParser = $parser;
    }
}
