<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\ParseContext;

class RootNodeParser extends AbstractNodeParser
{
    protected $classNodeParser;
    protected $functionNodeParser;
    protected $namespaceNodeParser;
    protected $useNodeParser;

    public function __construct()
    {
        parent::__construct();
        $this->classNodeParser = new ClassNodeParser();
        $this->functionNodeParser = new FunctionNodeParser();
        $this->namespaceNodeParser = new NamespaceNodeParser();
        $this->useNodeParser = new UseNodeParser();
    }
    /**
     * @return FileParseResult
     */
    public function parse(Node $node, ParseContext $ctx)
    {
        $nsNode = $this->astFinder->firstOfKind($node, \ast\AST_NAMESPACE);
        if (!is_null($nsNode)) {
            $namespace = $this->namespaceNodeParser->parse($nsNode, $ctx);
            $ctx->namespace = $namespace;
        }

        $ctx->addAliases($this->astFinder->parseWith($node, $ctx, \ast\AST_USE_ELEM, $this->useNodeParser));

        $classes = $this->astFinder->parseWith($node, $ctx, \ast\AST_CLASS, $this->classNodeParser);

        $functions = $this->astFinder->parseWith($node, $ctx, \ast\AST_FUNC_DECL, $this->functionNodeParser);

        return new FileParseResult($namespace, $classes, $functions);
    }
    public function injectClassNodeParser($nodeParser)
    {
        $this->classNodeParser = $nodeParser;
    }
    public function injectFunctionNodeParser($nodeParser)
    {
        $this->functionNodeParser = $nodeParser;
    }
    public function injectNamespaceNodeParser($nodeParser)
    {
        $this->namespaceNodeParser = $nodeParser;
    }
}
