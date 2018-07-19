<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\ASTParser;
use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\Entities\PHPProperty;
use \PDoc\NodeParsers\NodeParser;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class PropertyDeclarationNodeParser extends \atoum\test
{
    public function testParse()
    {
        $parser = new ASTParser();
        $stmts = $parser->parseFile(dirname(__DIR__) . '/fixtures/NodeParsers/PropertyDeclarationNodeParser/example.php');
        $node = $stmts->children[0]->children['stmts']->children[0];

        $ns = new PHPNamespace('Some\\Namespace');
        $ctx = new ParseContext('example.php', $ns);

        $source1 = new SourceLocation('example.php', 5);
        $property1 = new PHPProperty('bar', $source1, new DocBlock(), 'public', false, false, false);
        $source2 = new SourceLocation('exmaple.php', 6);
        $property2 = new PHPProperty('blah', $source2, new DocBlock(), 'public', false, false, false);

        $propParser = new class implements NodeParser {
            public function parse(\ast\Node $node, ParseContext $ctx)
            {
            }
        };

        $finder = new \mock\PDoc\ASTFinder();
        $this->calling($finder)->parseWith = [ $property1, $property2 ];

        $this->assert('2 properties defined together')
             ->given($this->newTestedInstance)
             ->if($this->testedInstance->injectASTFinder($finder))
             ->and($this->testedInstance->injectPropertyNodeParser($propParser))
             ->array($this->testedInstance->parse($node, $ctx))
             ->hasSize(2)
             ->object[0]->isEqualTo($property1)
             ->object[1]->isEqualTo($property2)
             ->mock($finder)
             ->call('parseWith')
             ->withArguments($node, new ParseContext('example.php', $ns, [], $node->flags), \ast\AST_PROP_ELEM, $propParser)
             ->once();
    }
}
