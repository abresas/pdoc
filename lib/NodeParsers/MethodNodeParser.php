<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPMethod;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class MethodNodeParser extends AbstractNodeParser
{
    protected $parameterNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->parameterNodeParser = new ParameterNodeParser();
    }
    public function parse(Node $node, ParseContext $ctx): PHPMethod
    {
        $parameters = $this->astFinder->parseWith($node, $ctx, \ast\AST_PARAM, $this->parameterNodeParser);

        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPMethod($node->children['name'], $sourceLoc, $docBlock, $parameters);
    }
}
