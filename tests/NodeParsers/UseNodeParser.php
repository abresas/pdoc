<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;

class UseNodeParser extends \atoum\test
{
    public function testParse()
    {
        $stmts = \ast\parse_file(dirname(__DIR__) . "/fixtures/NodeParsers/UseNodeParser/implicit.php", 50);
        $node = $stmts->children[0]->children[0];

        $this->assert('with implicit alias')
             ->given($this->newTestedInstance)
             ->and($ctx = new ParseContext('implicit.php', new PHPNamespace('Some\\Namespaced')))
             ->and($alias = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($alias->name)
             ->isEqualTo('\\Some\\Namespaced\\Foo')
             ->string($alias->alias)
             ->isEqualTo('Foo');

        $stmts = \ast\parse_file(dirname(__DIR__) . "/fixtures/NodeParsers/UseNodeParser/explicit.php", 50);
        $node = $stmts->children[0]->children[0];
        $this->assert('with explicit alias')
             ->given($this->newTestedInstance)
             ->and($ctx = new ParseContext('explicit.php', new PHPNamespace('Some\\Namespaced')))
             ->and($alias = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($alias->name)
             ->isEqualTo('\\Some\\Namespaced\\Foo')
             ->string($alias->alias)
             ->isEqualTo('Bar');
    }
}
