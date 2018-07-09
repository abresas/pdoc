<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use PDoc\Entities\PHPFunction;
use \PDoc\ParseContext;
use PDoc\SourceLocation;

class FunctionNodeParser extends AbstractNodeParser
{
    protected $parameterNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->parameterNodeParser = new ParameterNodeParser();
    }
    public function parse(Node $node, ParseContext $ctx): PHPFunction
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $parameters = $this->astFinder->parseWith($node, $ctx, \ast\AST_PARAM, $this->parameterNodeParser);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPFunction($node->children['name'], $sourceLoc, $docBlock, $parameters);
    }
    public function injectParameterNodeParser($parser)
    {
        $this->parameterNodeParser = $parser;
    }
}
