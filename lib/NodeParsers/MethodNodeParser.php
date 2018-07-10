<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPMethod;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;
use \PDoc\Types\AnyType;

class MethodNodeParser extends AbstractNodeParser
{
    protected $parameterNodeParser;
    public function __construct()
    {
        parent::__construct();
        $this->parameterNodeParser = new ParameterNodeParser();
        $this->typeNodeParser = new TypeNodeParser();
    }
    public function parse(Node $node, ParseContext $ctx): PHPMethod
    {
        $parameters = $this->astFinder->parseWith($node, $ctx, \ast\AST_PARAM, $this->parameterNodeParser);

        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        if (isset($node->children['returnType'])) {
            $returnType = $this->typeNodeParser->parse($node->children['returnType'], $ctx);
        } else {
            $returnType = new AnyType($sourceLoc);
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
        $isStatic = $node->flags & \ast\flags\MODIFIER_STATIC;
        $isAbstract = $node->flags & \ast\flags\MODIFIER_ABSTRACT;
        $isFinal = $node->flags & \ast\flags\MODIFIER_FINAL;
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
