<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Parse namespace definition, in the first line after opening tag of a PHP file.
 */
class NamespaceNodeParser extends AbstractNodeParser
{
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPNamespace
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $docComment = $node->children['docComment'] ?? '';
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        return new PHPNamespace($node->children['name']);
    }
}
