<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ASTFinder;
use \PDoc\ParseContext;

/**
 * Parse root node of a PHP file.
 *
 * The result of parsing a PHP file is a FileParseResult
 * that contains the namespace of the file, and classes and functions
 * that were defined in the file.
 */
class RootNodeParser extends AbstractNodeParser
{
    /** @var ClassNodeParser $classNodeParser Parser used for parsing classes. */
    protected $classNodeParser;
    /** @var FunctionNodeParser $functionNodeParser Parser used for parsing functions. */
    protected $functionNodeParser;
    /** @var NamespaceNodeParser $namespaceNodeParser Parser used for parsing namespaces. */
    protected $namespaceNodeParser;
    /** @var UseAlias $useNodeParser Parser used for parsing aliases. */
    protected $useNodeParser;
    /** @var ASTFinder $astFinder */
    private $astFinder;

    public function __construct()
    {
        parent::__construct();
        $this->classNodeParser = new ClassNodeParser();
        $this->functionNodeParser = new FunctionNodeParser();
        $this->namespaceNodeParser = new NamespaceNodeParser();
        $this->useNodeParser = new UseNodeParser();
        $this->astFinder = new ASTFinder();
    }

    /**
     * @param Node $node The root AST node of the file.
     * @param ParseContext $ctx The shared context for all parsers of the file.
     * @return FileParseResult
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $nsNode = $this->astFinder->firstOfKind($node, \ast\AST_NAMESPACE);
        if (!is_null($nsNode)) {
            $namespace = $this->namespaceNodeParser->parse($nsNode, $ctx);
            $ctx = new ParseContext($ctx->filePath, $namespace, $ctx->aliases, $ctx->astFlags);
        }

        $ctx->addAliases($this->astFinder->parseWith($node, $ctx, \ast\AST_USE_ELEM, $this->useNodeParser));

        $classes = $this->astFinder->parseWith($node, $ctx, \ast\AST_CLASS, $this->classNodeParser);

        $functions = $this->astFinder->parseWith($node, $ctx, \ast\AST_FUNC_DECL, $this->functionNodeParser);

        return new FileParseResult($namespace, $classes, $functions);
    }

    public function injectClassNodeParser($nodeParser): void
    {
        $this->classNodeParser = $nodeParser;
    }

    public function injectUseNodeParser($nodeParser): void
    {
        $this->useNodeParser = $nodeParser;
    }

    public function injectFunctionNodeParser($nodeParser): void
    {
        $this->functionNodeParser = $nodeParser;
    }

    public function injectNamespaceNodeParser($nodeParser): void
    {
        $this->namespaceNodeParser = $nodeParser;
    }

    /**
     * @param ASTFinder $finder
     */
    public function injectASTFinder($finder): void
    {
        $this->astFinder = $finder;
    }

}
