<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\ASTParser;
use \PDoc\Entities\PHPNamespace;
use \PDoc\SourceLocation;
use \PDoc\ParseContext;

class PropertyNodeParser extends \atoum\test
{
    public function testParse()
    {
        $this->given($this->newTestedInstance)
             ->and($ctx = new ParseContext('example.php', new PHPNamespace('Whatever'), [], \ast\flags\MODIFIER_PUBLIC | \ast\flags\MODIFIER_STATIC | \ast\flags\MODIFIER_FINAL))
             ->and($parser = new ASTParser())
             ->and($stmts = $parser->parseFile(dirname(__DIR__) . '/fixtures/NodeParsers/PropertyNodeParser/example.php'))
             ->and($node = $stmts->children[0]->children['stmts']->children[0]->children[0])
             ->if($prop = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($prop->name)->isEqualTo('bar')
             ->object($prop->sourceLocation)->isInstanceOf(SourceLocation::class)
             ->string($prop->sourceLocation->filePath)->isEqualTo('example.php')
             ->integer($prop->sourceLocation->line)->isEqualTo(4)
             ->string($prop->visibility)->isEqualTo('public')
             ->boolean($prop->isStatic)->isTrue()
             ->boolean($prop->isFinal)->isTrue()
             ->boolean($prop->isAbstract)->isFalse();
    }
    public function testParseUnexpectedVisibility()
    {
        $this->given($this->newTestedInstance)
             ->and($ctx = new ParseContext('example.php', new PHPNamespace('Whatever'), [], 0))
             ->and($node = new \ast\Node())
             ->and($node->children['name'] = 'foobar')
             ->and($node->lineno = 7)
             ->exception(function () use ($node, $ctx) {
                    $this->testedInstance->parse($node, $ctx);
             })
             ->hasMessage('example.php:7: Unexpected visibility on foobar');
    }
}
