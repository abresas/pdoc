<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPNamespace;
use \PDoc\SourceLocation;

class NamespaceNodeParser extends AbstractNodeParser
{
    public function parse(Node $node): PHPNamespace
    {
        $sourceLoc = new SourceLocation($this->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment);
        return new PHPNameSpace($node->children['name'], $sourceLoc, $docBlock);
    }
}
