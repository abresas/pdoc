<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

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
    public function parse(Node $node, string $filePath): FileParseResult
    {
        $nsNode = $this->astFinder->firstOfKind($node, \ast\AST_NAMESPACE);
        if (is_null($nsNode)) {
            $sourceLoc = new SourceLocation($filePath, $node->lineno);
            $namespace = new PHPNamespace('Global', $sourceLoc, new DocBlock('', '', []));
        } else {
            $namespace = $this->namespaceNodeParser->parse($nsNode, $filePath);
        }

        $ctx = new ParseContext($filePath, $namespace);

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
