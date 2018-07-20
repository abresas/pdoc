<?php
namespace PDoc\tests\unit\NodeParsers;

use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;

class RootNodeParser extends \atoum\test
{
    public function testParseWithNamespace()
    {
        $this->given($this->newTestedInstance)
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/RootNodeParser/withns.php', 50))
             ->and($ctx = new ParseContext('withns.php', new PHPNamespace('Global')))
             ->and($nsParser = new \mock\PDoc\NodeParsers\NamespaceNodeParser())
             ->and($classParser = new \mock\PDoc\NodeParsers\ClassNodeParser())
             ->and($funcParser = new \mock\PDoc\NodeParsers\FunctionNodeParser())
             ->and($useParser = new \mock\PDoc\NodeParsers\UseNodeParser())
             ->and($astFinder = new \mock\PDoc\ASTFinder())
             ->if($this->testedInstance->injectNamespaceNodeParser($nsParser))
             ->and($this->testedInstance->injectClassNodeParser($classParser))
             ->and($this->testedInstance->injectFunctionNodeParser($funcParser))
             ->and($this->testedInstance->injectASTFinder($astFinder))
             ->and($this->testedInstance->injectUseNodeParser($useParser))
             ->and($res = $this->testedInstance->parse($root, $ctx))
             ->then
             ->string($res->namespace->name)->isEqualTo('PDoc\\tests\\fixtures\\NodeParsers\\RootNodeParser')
             ->array($res->classes)->hasSize(2)
             ->array($res->functions)->hasSize(3)
             ->mock($nsParser)->call('parse')->once()
             ->mock($classParser)->call('parse')->twice()
             ->mock($funcParser)->call('parse')->thrice()
             ->mock($astFinder)->call('parseWith')->thrice();
    }
}
