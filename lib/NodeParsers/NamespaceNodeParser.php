<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class NamespaceNodeParser extends AbstractNodeParser
{
    public function parse(Node $node, string $filePath): PHPNamespace
    {
        $sourceLoc = new SourceLocation($filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $ctx = new ParseContext($filePath, new PHPNamespace('Global', $sourceLoc, new DocBlock('', '', [])));
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPNameSpace($node->children['name'], $sourceLoc, $docBlock);
    }
}
