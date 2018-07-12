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
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPClass
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);

        $propertiesPerDecl = $this->astFinder->parseWith($node, $ctx, \ast\AST_PROP_DECL, $this->propertyDeclarationNodeParser);
        $properties = [];
        foreach ($propertiesPerDecl as $declProperties) {
            $properties = array_merge($properties, $declProperties);
        }
        $methods = $this->astFinder->parseWith($node, $ctx, \ast\AST_METHOD, $this->methodNodeParser);

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        $name = $node->children['name'];
        if (isset($node->children['extends'])) {
            $extends = $ctx->resolve($node->children['extends']->children['name']);
        } else {
            $extends = null;
        }
        $class = new PHPClass($name, $ctx->namespace->name, $extends, $sourceLoc, $docBlock, $methods, $properties);

        return $class;
    }
    /**
     * @param PropertyDeclarationNodeParser $nodeParser
     */
    public function injectPropertyDeclarationNodeParser($nodeParser): void
    {
        $this->propertyDeclarationNodeParser = $nodeParser;
    }
    /**
     * @param MethodNodeParser $nodeParser
     */
    public function injectMethodNodeParser($nodeParser): void
    {
        $this->methodNodeParser = $nodeParser;
    }
}
