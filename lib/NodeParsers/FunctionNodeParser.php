<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use PDoc\Entities\PHPFunction;
use PDoc\SourceLocation;

class FunctionNodeParser extends AbstractNodeParser
{
    public function parse(Node $node)
    {
        $sourceLoc = new SourceLocation($this->filePath, $node->lineno);
        $parameters = $this->astFinder->parseWith($node, \ast\AST_PARAM, new ParameterNodeParser($this->filePath));
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment);
        return new PHPFunction($node->children['name'], $sourceLoc, $docBlock, $parameters);
    }
}
