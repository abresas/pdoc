<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ASTFinder;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Parse a statement that defines one or more properties of the same visibility.
 *
 * For example, "public $foo, $bar;"
 */
class PropertyDeclarationNodeParser extends AbstractNodeParser
{
    /** @var ASTFinder $astFinder */
    private $astFinder;
    /** @var PropertyNodeParser $propertyNodeParser The parser that handles each property that was defined in this statement. */
    private $propertyNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->astFinder = new ASTFinder();
        $this->propertyNodeParser = new PropertyNodeParser();
    }
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return \PDoc\Entities\PHPProperty[] Array of the properties that were defined.
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $declCtx = new ParseContext($ctx->filePath, $ctx->namespace, $ctx->aliases, $node->flags);
        $properties = $this->astFinder->parseWith($node, $declCtx, \ast\AST_PROP_ELEM, $this->propertyNodeParser);
        return $properties;
    }
    public function injectASTFinder($finder)
    {
        $this->astFinder = $finder;
    }
}
