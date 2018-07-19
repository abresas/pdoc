<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\ASTParser;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\Types\ClassType;
use \PDoc\Types\UnionType;
use \PDoc\Types\ArrayType;
use \PDoc\Types\DoubleType;
use \PDoc\Types\CallableType;
use \PDoc\Types\VoidType;
use \PDoc\Types\BoolType;
use \PDoc\Types\LongType;
use \PDoc\Types\FloatType;
use \PDoc\Types\StringType;
use \PDoc\Types\IterableType;
use \PDoc\Types\ObjectType;

class TypeNodeParser extends \atoum\test
{
    private function getFixtureStatements()
    {
        $parser = new ASTParser();
        $stmts = $parser->parseFile(dirname(__DIR__) . '/fixtures/NodeParsers/TypeNodeParser/types.php');
        return $stmts;
    }
    private function getFixtureParamType($i)
    {
        $stmts = $this->getFixtureStatements();
        $funcNode = $stmts->children[0];
        $node = $funcNode->children['params']->children[$i]->children['type'];
        return $node;
    }
    private function getFixtureReturnType($i)
    {
        $stmts = $this->getFixtureStatements();
        $funcNode = $stmts->children[0];
        $node = $funcNode->children['returnType'];
        return $node;
    }
    private function getParseContext()
    {
        $ns = new PHPNamespace('Some\\Namespace');
        return $ctx = new ParseContext('example.php', $ns);
    }
    public function testParseClass()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(0))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(ClassType::class);
    }
    public function testParseNullable()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(1))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(UnionType::class);
    }
    public function testParseArray()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(2))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(ArrayType::class);
    }
    public function testParseCallable()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(3))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(CallableType::class);
    }
    public function testParseVoid()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureReturnType(1))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(VoidType::class);
    }
    public function testParseBool()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(4))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(BoolType::class);
    }
    public function testParseInt()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(5))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(LongType::class);
    }
    public function testParseFloat()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(6))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(DoubleType::class);
    }
    public function testParseString()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(7))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(StringType::class);
    }
    public function testParseIterable()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(8))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(IterableType::class);
    }
    public function testParseObject()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = $this->getFixtureParamType(9))
             ->and($ctx = $this->getParseContext())
             ->object($type = $parser->parse($node, $ctx))
             ->isInstanceOf(ObjectType::class);
    }
    public function testParseUnexpectedType()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = new \ast\Node())
             ->and($node->kind = \ast\AST_TYPE)
             ->and($node->flags = -1)
             ->and($node->lineno = 5)
             ->and($ctx = $this->getParseContext())
             ->exception(function () use ($parser, $node, $ctx) {
                 $parser->parse($node, $ctx);
             })
             ->hasMessage('example.php:5: Unexpected AST_TYPE: -1');
    }
    public function testParseUnexpectedKind()
    {
        $this->given($parser = new \PDoc\NodeParsers\TypeNodeParser())
             ->and($node = new \ast\Node())
             ->and($node->kind = -1)
             ->and($node->lineno = 6)
             ->and($ctx = $this->getParseContext())
             ->exception(function () use ($parser, $node, $ctx) {
                 $parser->parse($node, $ctx);
             })
             ->hasMessage('example.php:6: Unexpected type node kind: -1');
    }
}
