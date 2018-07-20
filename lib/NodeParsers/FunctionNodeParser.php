<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ASTFinder;
use \PDoc\DocCommentParser;
use \PDoc\Entities\PHPFunction;
use \PDoc\NodeParsers\TypeNodeParser;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;
use \PDoc\Types\AnyType;

/**
 * Parse PHP functions.
 */
class FunctionNodeParser extends AbstractNodeParser
{
    /**
     * @var ParameterNodeParser $parameterNodeParser
     */
    private $parameterNodeParser;
    /** @var ASTFinder $astFinder */
    private $astFinder;
    /** @var DocCommentParser $docCommentParser */
    private $docCommentParser;
    /** @var TypeNodeParser $typeNodeParser */
    private $typeNodeParser;

    public function __construct()
    {
        parent::__construct();
        $this->astFinder = new ASTFinder();
        $this->docCommentParser = new DocCommentParser();
        $this->parameterNodeParser = new ParameterNodeParser();
        $this->typeNodeParser = new TypeNodeParser();
    }

    /**
     * @param Node $node
     * @param ParseContext $ctx
     * @return PHPFunction
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $sourceLoc = new SourceLocation($ctx->filePath, $node->lineno);
        $parameters = $this->astFinder->parseWith($node, $ctx, \ast\AST_PARAM, $this->parameterNodeParser);
        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment, $ctx, $sourceLoc);
        $returnType = $this->parseReturnType($node, $ctx);
        return new PHPFunction($node->children['name'], $sourceLoc, $returnType, $docBlock, $parameters);
    }

    /**
     * @param ParameterNodeParser $parser
     */
    public function injectParameterNodeParser(ParameterNodeParser $parser)
    {
        $this->parameterNodeParser = $parser;
    }

    /**
     * @param ASTFinder $finder
     */
    public function injectASTFinder(ASTFinder $finder)
    {
        $this->astFinder = $finder;
    }

    /**
     * @param TypeNodeParser $parser
     */
    public function injectTypeNodeParser(TypeNodeParser $parser)
    {
        $this->typeNodeParser = $parser;
    }

    /**
     * @param DocCommentParser $parser
     */
    public function injectDocCommentParser(DocCommentParser $parser)
    {
        $this->docCommentParser = $parser;
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
