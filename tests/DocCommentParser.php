<?php
namespace PDoc\tests\units;

use \mageekguy\atoum;

use \PDoc\DocBlock;
use \PDoc\Entities\PHPNamespace;
use \PDoc\ParseContext;
use \PDoc\SourceLocation;
use \PDoc\Tags\AbstractTag;
use \PDoc\Tags\ITagBuilder;

class DocCommentParser extends atoum
{
    public function testParseEmpty()
    {
        $docParser = new \PDoc\DocCommentParser();
        $parseContext = new ParseContext('example.php', new PHPNamespace('Some\\Namespace'));
        $sourceLocation = new SourceLocation('example.php', 12);

        $this->object($docParser->parse('', $parseContext, $sourceLocation))
             ->isEqualTo(new DocBlock('', '', []));
    }
    public function testParse()
    {
        $parseContext = new ParseContext('example.php', new PHPNamespace('Some\\Namespace'));
        $sourceLocation = new SourceLocation('example.php', 10);

        $tagBuilder = new \mock\PDoc\Tags\TagBuilder();
        $this->calling($tagBuilder)->build = function ($name, $args, $ctx, $loc) {
            if ($name === 'unsupported') {
                return null;
            } else {
                return new class($name, $args, $ctx, $loc) extends AbstractTag
                {
                    public function __construct($name, $args, $ctx, $loc)
                    {
                        $this->name = $name;
                        $this->args = $args;
                        $this->ctx = $ctx;
                        $this->loc = $loc;
                    }
                };
            }
        };

        $docParser = new \PDoc\DocCommentParser();
        $docParser->injectTagBuilder($tagBuilder);

        $this->assert('Pass bad arguments')
             ->exception(function () use ($docParser) {
                 $docParser->parse(null, null, null);
             });

        $comment = "/**\n@foo\n\nfoo\n*/";
        $b = $docParser->parse($comment, $parseContext, $sourceLocation);

        $this->assert('Parse comment without tags')
             ->given($b = $docParser->parse($comment, $parseContext, $sourceLocation))
             ->string($b->shortDescription)
             ->isEqualTo('')
             ->string($b->longDescription)
             ->isEqualTo("")
             ->array($b->tags)
             ->isEmpty()
             ->mock($tagBuilder)
             ->call('build')
             ->never();

        $comment = "/**
 * short
 * long
 */";
        $b = $docParser->parse($comment, $parseContext, $sourceLocation);

        $this->assert('Parse comment without tags')
             ->given($b = $docParser->parse($comment, $parseContext, $sourceLocation))
             ->string($b->shortDescription)
             ->isEqualTo('short')
             ->string($b->longDescription)
             ->isEqualTo("long\n")
             ->array($b->tags)
             ->isEmpty()
             ->mock($tagBuilder)
             ->call('build')
             ->never();

        $comment = "/**
 * short
 * long
 *
 * @supported foo bar
 * @unsupported red green
 */";

        $this->assert('Parse comment with tags')
             ->object($docParser->parse($comment, $parseContext, $sourceLocation))
             ->mock($tagBuilder)
             ->call('build')
             ->twice();
    }
}
