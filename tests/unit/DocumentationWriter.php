<?php
namespace PDoc\tests\unit;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\Entities\PHPClass;
use \PDoc\SourceLocation;

class DocumentationWriter extends \atoum\test
{
    public function testWrite()
    {
        $tl = new \mock\PDoc\Templates\TemplateLoader('./templates', 'default');
        $fw = new \mock\PDoc\FileSystem\FileWriter();
        $this->calling($fw)->writeFile = null;

        $dw = new \PDoc\DocumentationWriter();
        $dw->injectTemplateLoader($tl);
        $dw->injectFileWriter($fw);

        $this->assert('no namespaces')
             ->if($dw->write([]))
             ->then
             ->mock($fw)
             ->call('writeFile')
             ->exactly(2);

        $ns = new PHPNamespace('Some\\Namespace');
        $this->assert('one namespace but without classes')
             ->if($dw->write([$ns]))
             ->then
             ->mock($fw)
             ->call('writeFile')
             ->exactly(2);

        $c1 = new PHPClass('Example', '\\Some\\Namespace', null, new SourceLocation('example.php', 5), new DocBlock());
        $ns->addClass($c1);

        $this->assert('one namespace with one class')
             ->if($dw->write([$ns]))
             ->then
             ->mock($fw)
             ->call('writeFile')
             ->exactly(3)
             ->call('writeFile')
             ->withArguments(realpath('./docs/Some.Namespace.Example.html'))
             ->once();
    }
}
