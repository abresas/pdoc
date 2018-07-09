<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPProperty;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class PropertyNodeParser extends AbstractNodeParser
{
    public function parse(Node $node, ParseContext $ctx): PHPProperty
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        $property = new PHPProperty($node->children['name'], $sourceLoc, $docBlock);
        return $property;
    }
}
