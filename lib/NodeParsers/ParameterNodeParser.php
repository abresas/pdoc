<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPParameter;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class ParameterNodeParser extends AbstractNodeParser
{
    public function parse(Node $node, ParseContext $ctx): PHPParameter
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPParameter($node->children['name'], $sourceLoc, $docBlock);
    }
}
