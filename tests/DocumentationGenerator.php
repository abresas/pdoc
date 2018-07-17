<?php
namespace PDoc\tests\unit;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\Entities\PHPClass;
use \PDoc\NodeParsers\FileParseResult;
use \PDoc\SourceLocation;

class DocumentationGenerator extends \atoum\test
{
    public function testParseFiles()
    {
        $astParser = new \mock\PDoc\ASTParser();
        $node = new \ast\Node();
        $node->lineno = 1;
        $this->calling($astParser)->parseFile = $node;

        $rootParser = new \mock\PDoc\NodeParsers\RootNodeParser();
        $ns1 = new PHPNamespace('\\Some\\Namespace');
        $class1 = new PHPClass('FirstClass', $ns1->name, null, new SourceLocation('example.php', 10), new DocBlock());
        $class2 = new PHPClass('SecondClass', $ns1->name, null, new SourceLocation('example2.php', 5), new DocBlock());
        $this->calling($rootParser)->parse[0] = new FileParseResult($ns1, [$class1]);
        $this->calling($rootParser)->parse[1] = new FileParseResult($ns1, [$class2]);

        $gen = new \PDoc\DocumentationGenerator();
        $gen->injectASTParser($astParser);
        $gen->injectRootNodeParser($rootParser);

        $this->given($d = $gen->parseFiles([]))
             ->array($d->getNamespaces())
             ->isEmpty()
             ->mock($astParser)
             ->call('parseFile')
             ->never()
             ->mock($rootParser)
             ->call('parse')
             ->never();

        $this->given($d = $gen->parseFiles(['example1.php', 'example2.php']))
             ->array($d->getNamespaces())
             ->hasSize(1)
             ->hasKey('\\Some\\Namespace')
             ->given($n = $d->getNamespaces()['\\Some\\Namespace'])
             ->array($n->classes)
             ->hasSize(2)
             ->hasKey('FirstClass')
             ->hasKey('SecondClass')
             ->mock($astParser)
             ->call('parseFile')
             ->twice()
             ->mock($rootParser)
             ->call('parse')
             ->twice();
    }
}
