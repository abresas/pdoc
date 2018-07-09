<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPClass;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class ClassNodeParser extends AbstractNodeParser
{
    protected $propertyNodeParser;
    protected $methodNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->propertyNodeParser = new PropertyNodeParser();
        $this->methodNodeParser = new MethodNodeParser();
    }
    public function parse(Node $node, ParseContext $ctx): PHPClass
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);

        $properties = $this->astFinder->parseWith($node, $ctx, \ast\AST_PROP_ELEM, $this->propertyNodeParser);
        $methods = $this->astFinder->parseWith($node, $ctx, \ast\AST_METHOD, $this->methodNodeParser);

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        $class = new PHPClass($node->children['name'], $sourceLoc, $docBlock, $methods, $properties);

        return $class;
    }
    public function injectPropertyNodeParser($nodeParser)
    {
        $this->propertyNodeParser = $nodeParser;
    }
    public function injectMethodNodeParser($nodeParser)
    {
        $this->methodNodeParser = $nodeParser;
    }
}
