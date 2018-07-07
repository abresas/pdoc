<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPProperty;
use \PDoc\SourceLocation;

class PropertyNodeParser extends AbstractNodeParser
{
    public function parse(Node $node): PHPProperty
    {
        $sourceLoc = new SourceLocation($this->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment);
        $property = new PHPProperty($node->children['name'], $sourceLoc, $docBlock);
        return $property;
    }
}
