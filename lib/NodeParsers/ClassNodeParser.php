<?php
namespace PDoc\NodeParsers;

use \ast\Node;

use \PDoc\Entities\PHPClass;
use \PDoc\SourceLocation;

class ClassNodeParser extends AbstractNodeParser
{
    protected $propertyNodeParser;
    protected $methodNodeParser;
    public function __construct(string $filePath)
    {
        parent::__construct($filePath);
        $this->propertyNodeParser = new PropertyNodeParser($filePath);
        $this->methodNodeParser = new MethodNodeParser($filePath);
    }
    public function parse(Node $node): PHPClass
    {
        $sourceLoc = new SourceLocation($this->filePath, $node->lineno);

        $properties = $this->astFinder->parseWith($node, \ast\AST_PROP_ELEM, $this->propertyNodeParser);
        $methods = $this->astFinder->parseWith($node, \ast\AST_METHOD, $this->methodNodeParser);

        $docComment = $node->children['docComment'] ?? '';
        $docBlock = $this->parseDocComment($docComment);

        $class = new PHPClass($node->children['name'], $sourceLoc, $docBlock, $methods, $properties);

        return $class;
    }
    public function injectPropertyNodeParser($nodeParser)
    {
        $this->propertyNodeParser = $nodeParser;
    }
    public function injectMethodNodeParser($nodeParser)
    {
        $this->methodNodeParser = $nodeParser;
    }
}
