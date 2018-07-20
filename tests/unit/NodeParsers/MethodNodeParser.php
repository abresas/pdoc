<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\Types\AnyType;
use \PDoc\Types\ArrayType;
use \PDoc\Types\LongType;
use \PDoc\Types\StringType;

class MethodNodeParser extends \atoum\test
{
    public function testParseWithReturnType()
    {
        $this->given($this->newTestedInstance)
             ->and($typeParser = new \mock\PDoc\NodeParsers\TypeNodeParser())
             ->and($paramParser = new \mock\PDoc\NodeParsers\ParameterNodeParser())
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/MethodNodeParser/withreturntype.php', 50))
             ->and($node = $root->children[1]->children['stmts']->children[0])
             ->and($ctx = new ParseContext('withreturntype.php', new PHPNamespace('Whatever')))
             ->if($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($this->testedInstance->injectParameterNodeParser($paramParser))
             ->and($method = $this->testedInstance->parse($node, $ctx))
             ->then
             ->mock($typeParser)->call('parse')->withArguments($node->children['returnType'], $ctx)->once()
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[0], $ctx)->once()
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[1], $ctx)->once()
             ->string($method->name)->isEqualTo('foo')
             ->object($method->returnType)->isInstanceOf(ArrayType::class)
             ->boolean($method->isStatic)->isTrue()
             ->boolean($method->isFinal)->isTrue()
             ->boolean($method->isAbstract)->isFalse()
             ->array($method->parameters)->hasSize(2)->hasKey('x')->hasKey('y');
    }

    public function testParseWithoutReturnType()
    {
        $this->given($this->newTestedInstance)
             ->and($typeParser = new \mock\PDoc\NodeParsers\TypeNodeParser())
             ->and($paramParser = new \mock\PDoc\NodeParsers\ParameterNodeParser())
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/MethodNodeParser/withoutreturntype.php', 50))
             ->and($node = $root->children[1]->children['stmts']->children[0])
             ->and($ctx = new ParseContext('withreturntype.php', new PHPNamespace('Whatever')))
             ->if($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($this->testedInstance->injectParameterNodeParser($paramParser))
             ->and($method = $this->testedInstance->parse($node, $ctx))
             ->then
             ->mock($typeParser)->call('parse')->never()
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[0], $ctx)->once()
             ->mock($paramParser)->call('parse')->withArguments($node->children['params']->children[1], $ctx)->once()
             ->string($method->name)->isEqualTo('foo')
             ->object($method->returnType)->isInstanceOf(AnyType::class)
             ->boolean($method->isStatic)->isTrue()
             ->boolean($method->isFinal)->isFalse()
             ->boolean($method->isAbstract)->isTrue()
             ->array($method->parameters)->hasSize(2)->hasKey('x')->hasKey('y');
    }

    public function testParseWithUnexpectedVisibility()
    {
        $this->given($this->newTestedInstance)
             ->and($typeParser = new \mock\PDoc\NodeParsers\TypeNodeParser())
             ->and($paramParser = new \mock\PDoc\NodeParsers\ParameterNodeParser())
             ->and($node = new \ast\Node())
             ->and($node->lineno = 8)
             ->and($node->flags = 0)
             ->and($node->children = ['name' => 'bar'])
             ->and($ctx = new ParseContext('withreturntype.php', new PHPNamespace('Whatever')))
             ->if($this->testedInstance->injectTypeNodeParser($typeParser))
             ->and($this->testedInstance->injectParameterNodeParser($paramParser))
             ->then
             ->exception(function () use ($node, $ctx) {
                    $this->testedInstance->parse($node, $ctx);
             })
             ->hasMessage('withreturntype.php:8: Unexpected visibility on bar');
    }
}
