<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use PDoc\Entities\PHPFunction;
use \PDoc\ParseContext;
use PDoc\SourceLocation;

/**
 * Parse PHP functions.
 */
class FunctionNodeParser extends AbstractNodeParser
{
    /**
     * @var ParameterNodeParser $parameterNodeParser
     */
    protected $parameterNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->parameterNodeParser = new ParameterNodeParser();
    }
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPFunction
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $parameters = $this->astFinder->parseWith($node, $ctx, \ast\AST_PARAM, $this->parameterNodeParser);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPFunction($node->children['name'], $sourceLoc, $docBlock, $parameters);
    }
    /**
     * @param ParameterNodeParser $parser
     */
    public function injectParameterNodeParser($parser)
    {
        $this->parameterNodeParser = $parser;
    }
}
