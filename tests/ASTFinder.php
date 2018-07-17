<?php
namespace PDoc\tests\units;

use \mageekuy\atoum;
use \ast\Node;

use \PDoc\ParseContext;
use \PDoc\Entities\PHPNamespace;

class ASTFinder extends \atoum\test
{
    public function testFirstOfKind()
    {
        $code = "<?php
            function foo() {
                return 'red';
            }
            function bar() {
                return 'green';
            }
        ";
        $root = \ast\parse_code($code, 50);
        $f = new \PDoc\ASTFinder();
        $node = $f->firstOfKind($root, \ast\AST_FUNC_DECL);
        $this->string($node->children['name'])->isEqualTo('foo');
    }
    public function testByKind()
    {
        $code = "<?php
            function foo() {
                return 'red';
            }
            function bar() {
                return 'green';
            }
        ";
        $root = \ast\parse_code($code, 50);
        $f = new \PDoc\ASTFinder();
        $nodes = $f->byKind($root, \ast\AST_FUNC_DECL);
        $this->array($nodes)->hasSize(2);
        $this->string($nodes[0]->children['name'])->isEqualTo('foo');
        $this->string($nodes[1]->children['name'])->isEqualTo('bar');
    }
    public function testParseWith()
    {
        $code = "<?php
            function foo() {
                return 'red';
            }
            function bar() {
                return 'green';
            }
        ";
        $root = \ast\parse_code($code, 50);
        $f = new \PDoc\ASTFinder();

        $parser = new class implements \PDoc\NodeParsers\NodeParser {
            public function parse(\ast\Node $node, ParseContext $ctx)
            {
                return $ctx->filePath . ':' . $node->lineno . ':' . $node->children['name'];
            }
        };
        $ctx = new ParseContext("example.php", new PHPNamespace("\\Some\\Namespace"));
        $res = $f->parseWith($root, $ctx, \ast\AST_FUNC_DECL, $parser);
        $this->array($res)->string[0]->isEqualTo("example.php:2:foo")
                          ->string[1]->isEqualTo("example.php:5:bar")
                          ->hasSize(2);
    }
}
