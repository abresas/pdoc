<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPMethod;
use \PDoc\SourceLocation;

class MethodNodeParser extends AbstractNodeParser
{
    protected $parameterNodeParser;
    public function __construct(string $filePath)
    {
        parent::__construct($filePath);
        $this->parameterNodeParser = new ParameterNodeParser($filePath);
    }
    public function parse(Node $node): PHPMethod
    {
        $parameters = $this->astFinder->parseWith($node, \ast\AST_PARAM, $this->parameterNodeParser);

        $sourceLoc = new SourceLocation($this->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment);
        return new PHPMethod($node->children['name'], $sourceLoc, $docBlock, $parameters);
    }
}
