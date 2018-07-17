<?php
namespace PDoc\tests\units;

use \PDoc\Tags\ReturnTag;
use \PDoc\Types\AnyType;

class DocBlock extends \atoum\test
{
    public function testConstructor()
    {
        $this->given($tags = [ new ReturnTag(new AnyType(), 'return 1'), new ReturnTag(new AnyType(), 'return 2') ])
             ->if($this->newTestedInstance('short description', 'long description', $tags))
             ->then
             ->string($this->testedInstance->shortDescription)
             ->isEqualTo('short description')
             ->string($this->testedInstance->longDescription)
             ->isEqualTo('long description')
             ->array($this->testedInstance->tags)
             ->isIdenticalTo($tags);
    }
}
