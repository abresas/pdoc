<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;
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
    /** @var ASTFinder $astFinder */
    private $astFinder;
    /** @var DocCommentParser $docCommentParser */
    private $docCommentParser;

    public function __construct()
    {
        parent::__construct();
        $this->docCommentParser = new DocCommentParser();
        $this->parameterNodeParser = new ParameterNodeParser();
        $this->typeNodeParser = new TypeNodeParser();
        $this->astFinder = new ASTFinder();
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
        $returnType = $this->parseReturnType($node, $ctx);

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        $visibility = $this->parseVisibility($node->flags, $sourceLoc, $node->children['name']);
        $isStatic = (bool) ($node->flags & \ast\flags\MODIFIER_STATIC);
        $isAbstract = (bool) ($node->flags & \ast\flags\MODIFIER_ABSTRACT);
        $isFinal = (bool) ($node->flags & \ast\flags\MODIFIER_FINAL);
        return new PHPMethod($node->children['name'], $sourceLoc, $docBlock, $returnType, $visibility, $isStatic, $isAbstract, $isFinal, $parameters);
    }

    /**
     * Parse AST method node return typehint.
     * @param Node $node
     * @param ParseContext $ctx
     * @return \PDoc\Types\AbstractType Is AnyType if not specified.
     */
    private function parseReturnType(Node $node, ParseContext $ctx)
    {
        if (isset($node->children['returnType'])) {
            return $this->typeNodeParser->parse($node->children['returnType'], $ctx);
        } else {
            return new AnyType();
        }
    }

    /**
     * Parse AST method node to generate its visibility (public, protected or private).
     * @param int $flags The AST flags set in the current method node
     * @param SourceLocation $sourceLoc
     * @param string $nodeName The method name according to AST
     * @return string "public", "protected" or "private"
     */
    private function parseVisibility(int $flags, SourceLocation $sourceLoc, string $nodeName)
    {
        $flagsMap = [
            \ast\flags\MODIFIER_PUBLIC => 'public',
            \ast\flags\MODIFIER_PROTECTED => 'protected',
            \ast\flags\MODIFIER_PRIVATE => 'private'
        ];
        foreach ($flagsMap as $flag => $visibility) {
            if ($flags & $flag) {
                return $visibility;
            }
        }
        throw new \Exception($sourceLoc . ': Unexpected visibility on ' . $nodeName);
    }

    public function injectParameterNodeParser(ParameterNodeParser $parser)
    {
        $this->parameterNodeParser = $parser;
    }

    public function injectTypeNodeParser(TypeNodeParser $parser)
    {
        $this->typeNodeParser = $parser;
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
