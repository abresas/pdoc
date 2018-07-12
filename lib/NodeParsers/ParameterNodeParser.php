<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPParameter;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;
use \PDoc\Types\AnyType;

class ParameterNodeParser extends AbstractNodeParser
{
    /** @var TypeNodeParser $typeNodeParser */
    private $typeNodeParser;
    public function __construct()
    {
        $this->typeNodeParser = new TypeNodeParser();
        parent::__construct();
    }
    public function parse(Node $node, ParseContext $ctx): PHPParameter
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        if (!empty($node->children['type'])) {
            $type = $this->typeNodeParser->parse($node->children['type'], $ctx);
        } else {
            $type = new AnyType();
        }
        return new PHPParameter($node->children['name'], $type, $sourceLoc, $docBlock);
    }
}
