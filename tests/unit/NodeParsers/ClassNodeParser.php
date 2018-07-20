<?php
namespace PDoc\tests\units\NodeParsers;

use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;

class ClassNodeParser extends \atoum\test
{
    public function testParseWithoutParent()
    {
        $this->given($this->newTestedInstance)
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/ClassNodeParser/classes.php', 50))
             ->and($node = $root->children[0])
             ->and($ctx = new ParseContext('classes.php', new PHPNamespace('Whatever')))
             ->and($astFinder = new \mock\PDoc\ASTFinder())
             ->and($docParser = new \mock\PDoc\DocCommentParser())
             ->and($propParser = new \mock\PDoc\NodeParsers\PropertyDeclarationNodeParser())
             ->and($methodParser = new \mock\PDoc\NodeParsers\MethodNodeParser())
             ->if($this->testedInstance->injectASTFinder($astFinder))
             ->and($this->testedInstance->injectDocCommentParser($docParser))
             ->and($this->testedInstance->injectPropertyDeclarationNodeParser($propParser))
             ->and($this->testedInstance->injectMethodNodeParser($methodParser))
             ->and($class = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($class->name)->isEqualTo('TheParent')
             ->string($class->namespace)->isEqualTo('Whatever')
             ->variable($class->extends)->isNull()
             ->array($class->properties)->hasSize(4)
             ->array($class->methods)->hasSize(2)
             ->mock($astFinder)->call('parseWith')->withArguments($node, $ctx, \ast\AST_METHOD, $methodParser)->once()
             ->mock($astFinder)->call('parseWith')->withArguments($node, $ctx, \ast\AST_PROP_DECL, $propParser)->once()
             ->mock($docParser)->call('parse')->withArguments('', $ctx, new SourceLocation('classes.php', 3))->once()
             ->mock($propParser)->call('parse')->thrice()
             ->mock($methodParser)->call('parse')->twice();
    }
    public function testParseChild()
    {
        $this->given($this->newTestedInstance)
             ->and($root = \ast\parse_file(dirname(__DIR__) . '/fixtures/NodeParsers/ClassNodeParser/classes.php', 50))
             ->and($node = $root->children[1])
             ->and($ctx = new ParseContext('classes.php', new PHPNamespace('Whatever')))
             ->if($class = $this->testedInstance->parse($node, $ctx))
             ->then
             ->string($class->name)->isEqualTo('Child')
             ->string($class->namespace)->isEqualTo('Whatever')
             ->variable($class->extends)->isEqualTo('\\Whatever\\TheParent');
    }
}
