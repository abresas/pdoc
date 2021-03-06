<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;
use \PDoc\Entities\PHPClass;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

/**
 * Parse a class definition.
 *
 * Parse the phpDoc comment above the class, and use further parsers
 * to process its methods and properties.
 */
class ClassNodeParser extends AbstractNodeParser
{
    /** @var PropertyDeclarationNodeParser $propertyDeclarationNodeParser */
    private $propertyDeclarationNodeParser;
    /** @var DocCommentParser $docCommentParser */
    private $docCommentParser;
    /** @var MethodNodeParser $methodNodeParser */
    private $methodNodeParser;
    /** @var ASTFinder $astFinder */
    private $astFinder;

    public function __construct()
    {
        parent::__construct();
        $this->astFinder = new ASTFinder();
        $this->docCommentParser = new DocCommentParser();
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

        $properties = $this->parseProperties($node, $ctx);
        $methods = $this->parseMethods($node, $ctx);
        $extends = $this->getParentClassName($node, $ctx);

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        $name = $node->children['name'];
        $class = new PHPClass($name, $ctx->namespace->name, $extends, $sourceLoc, $docBlock, $methods, $properties);

        return $class;
    }

    /**
     * Generate class properties from class AST node and parser context.
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPProperty[]
     */
    private function parseProperties(Node $node, ParseContext $ctx): array
    {
        $propertiesPerDecl = $this->astFinder->parseWith($node, $ctx, \ast\AST_PROP_DECL, $this->propertyDeclarationNodeParser);
        // propertiesPerDecl is an array of arrays of properties.
        // flatten it to an array of properties.
        $properties = [];
        foreach ($propertiesPerDecl as $declProperties) {
            $properties = array_merge($properties, $declProperties);
        }
        return $properties;
    }

    /**
     * Generate class methods from class AST node and parser context.
     * @param Node $node Class definition AST node.
     * @param ParseContext $ctx Parser context.
     * @return PHPMethod[]
     */
    private function parseMethods(Node $node, ParseContext $ctx): array
    {
        return $this->astFinder->parseWith($node, $ctx, \ast\AST_METHOD, $this->methodNodeParser);
    }

    /**
     * Get parent class name, if specified.
     * @param Node $node The AST node that defines a class.
     * @return string|null The full namespaced parent class name if specified, otherwise null.
     */
    private function getParentClassName(Node $node, ParseContext $ctx): ?string
    {
        if (isset($node->children['extends'])) {
            return $ctx->resolve($node->children['extends']->children['name']);
        } else {
            return null;
        }
    }

    /**
     * @param ASTFinder $finder
     */
    public function injectASTFinder($finder): void
    {
        $this->astFinder = $finder;
    }

    /**
     * @param PropertyDeclarationNodeParser $nodeParser Parser used for parsing properties.
     */
    public function injectPropertyDeclarationNodeParser($nodeParser): void
    {
        $this->propertyDeclarationNodeParser = $nodeParser;
    }

    /**
     * @param MethodNodeParser $nodeParser Parser used for parsing methods.
     */
    public function injectMethodNodeParser($nodeParser): void
    {
        $this->methodNodeParser = $nodeParser;
    }

    /**
     * @param DocCommentParser $parser
     */
    public function injectDocCommentParser($parser): void
    {
        $this->docCommentParser = $parser;
    }

    /**
     * Parse the doc comment found above the currently parsed node.
     * @param string $docComment The text of the phpDoc comment.
     * @param ParseContext $ctx The state of the parser when parsing this node.
     * @param SourceLocation $sourceLoc The file and line where the current node was found.
     */
    private function parseDocComment(string $docComment, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        return $this->docCommentParser->parse($docComment, $ctx, $sourceLoc);
    }
}
