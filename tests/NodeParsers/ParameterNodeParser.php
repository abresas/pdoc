<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\ASTParser;
use \PDoc\Entities\PHPNamespace;
use \PDoc\NodeParsers\NodeParser;
use \PDoc\ParseContext;
use \PDoc\Types\AbstractType;
use \PDoc\Types\AnyType;

class ParameterNodeParser extends \atoum\test
{
    public function testParseWithType()
    {
        $customType = new class extends AbstractType {
        };
        $typeParser = new \mock\PDoc\NodeParser\TypeNodeParser();
        $this->calling($typeParser)->parse = $customType;
        $this->given($this->newTestedInstance)
             ->and($astParser = new ASTParser())
             ->and($root = $astParser->parseFile(dirname(__DIR__) . '/fixtures/NodeParsers/ParameterNodeParser/example1.php'))
             ->and($node = $root->children[0]->children['params']->children[0])
             ->and($ctx = new ParseContext('example1.php', new PHPNamespace('Whatever')))
             ->if($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($param = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($param->name)->isEqualTo('x')
             ->object($param->type)->isIdenticalTo($customType)
             ->mock($typeParser)->call('parse')->once();
    }
    public function testParseWithoutType()
    {
        $typeParser = new \mock\PDoc\NodeParser\TypeNodeParser();
        $this->calling($typeParser)->parse = new AnyType();
        $this->given($this->newTestedInstance)
             ->and($astParser = new ASTParser())
             ->and($root = $astParser->parseFile(dirname(__DIR__) . '/fixtures/NodeParsers/ParameterNodeParser/example2.php'))
             ->and($node = $root->children[0]->children['params']->children[0])
             ->and($ctx = new ParseContext('example2.php', new PHPNamespace('Whatever')))
             ->if($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($param = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($param->name)->isEqualTo('x')
             ->object($param->type)->isInstanceOf(AnyType::class)
             ->mock($typeParser)->call('parse')->never();
    }
}
