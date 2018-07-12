<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class NamespaceNodeParser extends AbstractNodeParser
{
    public function parse(Node $node, ParseContext $ctx)
    {
        $docComment = $node->children['docComment'] ?? '';
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPNameSpace($node->children['name'], $sourceLoc, $docBlock);
    }
}
