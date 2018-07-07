<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\SourceLocation;

class RootNodeParser extends AbstractNodeParser
{
    protected $classNodeParser;
    protected $functionNodeParser;

    public function __construct(string $filePath)
    {
        parent::__construct($filePath);
        $this->classNodeParser = new ClassNodeParser($filePath);
        $this->functionNodeParser = new FunctionNodeParser($this->filePath);
    }
    public function parse(Node $node): PHPNamespace
    {
        $nsNode = $this->astFinder->firstOfKind($node, \ast\AST_NAMESPACE);
        if (is_null($nsNode)) {
            $sourceLoc = new SourceLocation($this->filePath, $node->lineno);
            $namespace = new PHPNamespace('Global', $sourceLoc, new DocBlock('', '', []));
        } else {
            $astParser = new NamespaceNodeParser($this->filePath);
            $namespace = $astParser->parse($nsNode);
        }

        $classes = $this->astFinder->parseWith($node, \ast\AST_CLASS, $this->classNodeParser);
        $namespace->addClasses($classes);

        $functions = $this->astFinder->parseWith($node, \ast\AST_FUNC_DECL, $this->functionNodeParser);
        $namespace->addFunctions($functions);

        return $namespace;
    }
    public function injectClassNodeParser($nodeParser)
    {
        $this->classNodeParser = $nodeParser;
    }
    public function injectFunctionNodeParser($nodeParser)
    {
        $this->functionNodeParser = $nodeParser;
    }
}
