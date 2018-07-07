<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\SourceLocation;
use \PDoc\Entities\PHPParameter;

class ParameterNodeParser extends AbstractNodeParser
{
    public function parse(Node $node)
    {
        $sourceLoc = new SourceLocation($this->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment);
        return new PHPParameter($node->children['name'], $sourceLoc, $docBlock);
    }
}
