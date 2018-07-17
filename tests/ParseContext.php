<?php
namespace PDoc\tests\units;

use \mageekuy\atoum;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\Entities\UseAlias;
use \PDoc\SourceLocation;

class ParseContext extends \atoum\test
{
    public function testResolve()
    {
        $p = new \PDoc\ParseContext('filename.php', new PHPNamespace('Some\\Namespace'));
        $this->string($p->resolve('Foo'))->isEqualTo('\\Some\\Namespace\\Foo');
        $this->string($p->resolve('\\Foo'))->isEqualTo('\\Foo');
        $p->addAlias(new UseAlias('\\Some\\Other\\Foo', 'Foo'));
        $this->string($p->resolve('Foo'))->isEqualTo('\\Some\\Other\\Foo');
        $this->string($p->resolve('\\Foo'))->isEqualTo('\\Foo');
    }

    public function testAddAlias()
    {
        $p = new \PDoc\ParseContext('filename.php', new PHPNamespace('Some\\Namespace'));
        $this->given($u = new UseAlias('\\Some\\Other\\Foo', 'Bar'))
             ->if($p->addAlias($u))
             ->string($p->resolve('Bar'))
             ->isEqualTo('\\Some\\Other\\Foo');
    }

    public function testAddAliases()
    {
        $p = new \PDoc\ParseContext('filename.php', new PHPNamespace('Some\\Namespace'));
        $this->given($u1 = new UseAlias('\\Some\\Other\\Foo', 'Foo'))
             ->given($u2 = new UseAlias('\\Some\\Other\\Bar', 'Blah'))
             ->if($p->addAliases([$u1, $u2]))
             ->string($p->resolve('Foo'))
             ->isEqualTo('\\Some\\Other\\Foo')
             ->string($p->resolve('Bar'))
             ->isEqualTo('\\Some\\Namespace\\Bar')
             ->string($p->resolve('Blah'))
             ->isEqualTo('\\Some\\Other\\Bar');
    }
}
