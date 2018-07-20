<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\Types\AnyType;
use \PDoc\Types\ArrayType;

class FunctionNodeParser extends \atoum\test
{
    public function testParseWithReturnType()
    {
        $this->given($this->newTestedInstance)
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/FunctionNodeParser/example1.php', 50))
             ->and($node = $root->children[1])
             ->and($ctx = new ParseContext('example1.php', new PHPNamespace('Whatever')))
             ->and($paramParser = new \mock\PDoc\NodeParsers\ParameterNodeParser())
             ->and($typeParser = new \mock\PDoc\NodeParsers\TypeNodeParser())
             ->and($astFinder = new \mock\PDoc\ASTFinder())
             ->if($this->testedInstance->injectParameterNodeParser($paramParser))
             ->and($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($this->testedInstance->injectASTFinder($astFinder))
             ->and($func = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($func->name)->isEqualTo('foo')
             ->object($func->returnType)->isInstanceOf(ArrayType::class)
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[0], $ctx)->once()
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[1], $ctx)->once()
             ->mock($typeParser)->call('parse')->withArguments($node->children['returnType'], $ctx)->once()
             ->mock($astFinder)->call('parseWith')->once();
    }
    public function testParseWithoutReturnType()
    {
        $this->given($this->newTestedInstance)
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/FunctionNodeParser/example2.php', 50))
             ->and($node = $root->children[1])
             ->and($ctx = new ParseContext('example1.php', new PHPNamespace('Whatever')))
             ->and($paramParser = new \mock\PDoc\NodeParsers\ParameterNodeParser())
             ->and($typeParser = new \mock\PDoc\NodeParsers\TypeNodeParser())
             ->and($docParser = new \mock\PDoc\DocCommentParser())
             ->and($astFinder = new \mock\PDoc\ASTFinder())
             ->if($this->testedInstance->injectParameterNodeParser($paramParser))
             ->and($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($this->testedInstance->injectASTFinder($astFinder))
             ->and($this->testedInstance->injectDocCommentParser($docParser))
             ->and($func = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($func->name)->isEqualTo('bar')
             ->string($func->shortDescription)->isEqualTo('Test function.')
             ->object($func->returnType)->isInstanceOf(AnyType::class)
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[0], $ctx)->once()
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[1], $ctx)->once()
             ->mock($docParser)->call('parse')->withArguments($node->children['docComment'], $ctx)->once()
             ->mock($typeParser)->call('parse')->never()
             ->mock($astFinder)->call('parseWith')->once();
    }
}
