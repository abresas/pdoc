<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPClass;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class ClassNodeParser extends AbstractNodeParser
{
    protected $propertyDeclarationNodeParser;
    protected $methodNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->propertyDeclarationNodeParser = new PropertyDeclarationNodeParser();
        $this->methodNodeParser = new MethodNodeParser();
    }
    public function parse(Node $node, ParseContext $ctx): PHPClass
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        var_dump("parsing " . $node->children['name']);

        $propertiesPerDecl = $this->astFinder->parseWith($node, $ctx, \ast\AST_PROP_DECL, $this->propertyDeclarationNodeParser);
        $properties = [];
        foreach ($propertiesPerDecl as $declProperties) {
            $properties = array_merge($properties, $declProperties);
        }
        $methods = $this->astFinder->parseWith($node, $ctx, \ast\AST_METHOD, $this->methodNodeParser);

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        $class = new PHPClass($node->children['name'], $sourceLoc, $docBlock, $methods, $properties);

        return $class;
    }
    public function injectPropertyDeclarationNodeParser($nodeParser)
    {
        $this->propertyDeclarationNodeParser = $nodeParser;
    }
    public function injectMethodNodeParser($nodeParser)
    {
        $this->methodNodeParser = $nodeParser;
    }
}
