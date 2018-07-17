<?php
namespace PDoc\tests\units;

use \mageekuy\atoum;
use \ast\Node;

class ASTParser extends \atoum\test
{
    public function testParseFile()
    {
        $this->if($this->newTestedInstance)
             ->then
             ->object($this->testedInstance->parseFile(__DIR__ . '/fixtures/ASTParser/example.php'))
             ->isInstanceOf(Node::class);
    }
}
