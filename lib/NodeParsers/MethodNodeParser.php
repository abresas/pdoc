<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPMethod;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;
use \PDoc\Types\AnyType;

/**
 * Parse a methods of a class to calculate attributes useful for documentation.
 */
class MethodNodeParser extends AbstractNodeParser
{
    /** @var ParameterNodeParser $parameterNodeParser */
    private $parameterNodeParser;
    /** @var TypeNodeParser $typeNodeParser */
    private $typeNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->parameterNodeParser = new ParameterNodeParser();
        $this->typeNodeParser = new TypeNodeParser();
    }
    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPMethod
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $parameters = $this->astFinder->parseWith($node, $ctx, \ast\AST_PARAM, $this->parameterNodeParser);

        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        if (isset($node->children['returnType'])) {
            $returnType = $this->typeNodeParser->parse($node->children['returnType'], $ctx);
        } else {
            $returnType = new AnyType();
        }

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);

        if ($node->flags & \ast\flags\MODIFIER_PUBLIC) {
            $visibility = 'public';
        } elseif ($node->flags & \ast\flags\MODIFIER_PROTECTED) {
            $visibility = 'protected';
        } elseif ($node->flags & \ast\flags\MODIFIER_PRIVATE) {
            $visibility = 'private';
        } else {
            error_log($sourceLoc . ': Unexpected visibility on ' . $node->children['name']);
        }
        $isStatic = (bool)($node->flags & \ast\flags\MODIFIER_STATIC);
        $isAbstract = (bool)($node->flags & \ast\flags\MODIFIER_ABSTRACT);
        $isFinal = (bool)($node->flags & \ast\flags\MODIFIER_FINAL);
        return new PHPMethod($node->children['name'], $sourceLoc, $docBlock, $returnType, $visibility, $isStatic, $isAbstract, $isFinal, $parameters);
    }
    public function injectParameterNodeParser()
    {
        $this->parameterNodeParser = new ParameterNodeParser();
    }
    public function injectTypeNodeParser()
    {
        $this->typeNodeParser = new TypeNodeParser();
    }
}
