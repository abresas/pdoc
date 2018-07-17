<?php
namespace PDoc\Tags;

use PDoc\ParseContext;
use PDoc\SourceLocation;
use PDoc\Types\ClassType;
use PDoc\Types\TypeFactory;

/**
 * Construct a Tag instance from phpDoc string.
 */
class TagBuilder implements ITagBuilder
{
    /** @var TypeFactory $typeFactory */
    private $typeFactory;

    public function __construct()
    {
        $this->typeFactory = new TypeFactory();
    }

    /**
     * @param string $name The tag that was found (without leading @ symbol).
     * @param string[] $arguments List of words that were found after the tag.
     * @param ParseContext $ctx The context of the parser when it was found.
     * @param SourceLocation $sourceLoc File and line of the symbol whose tag this is.
     * @return AbstractTag|null
     */
    public function build(string $name, array $arguments, ParseContext $ctx, SourceLocation $sourceLoc): ?AbstractTag
    {
        // this could be implemented as a lookup in name => class array
        // but that would hinder static analysis tools.
        if ($name === 'param') {
            return $this->buildParamTag($arguments, $ctx, $sourceLoc);
        } elseif ($name === 'var') {
            return $this->buildVarTag($arguments, $ctx, $sourceLoc);
        } elseif ($name === 'return') {
            return $this->buildReturnTag($arguments, $ctx, $sourceLoc);
        } elseif ($name === 'suppress') {
            return null; // not a documentation tag
        } else {
            error_log($sourceLoc . ': Unsupported tag @' . $name);
            return null;
        }
    }

    private function buildParamTag(array $arguments, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        if (count($arguments) < 2) {
            error_log($sourceLoc . ": @param tag expects at least 2 arguments");
            return null;
        }
        $description = implode(" ", array_slice($arguments, 2));
        $type = $this->typeFactory->fromDocumentationString($arguments[0], $ctx);
        return new ParamTag($type, substr($arguments[1], 1), $description);
    }

    private function buildVarTag(array $arguments, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        $description = implode(" ", array_slice($arguments, 2));
        if (count($arguments) < 2) {
            error_log($sourceLoc . ": @var tag expects at least 2 arguments");
            return null;
        }
        $name = substr($arguments[1], 1);
        $type = $this->typeFactory->fromDocumentationString($arguments[0], $ctx);
        return new VarTag($type, $name, $description);
    }

    private function buildReturnTag(array $arguments, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        if (empty($arguments)) {
            error_log($sourceLoc . ": @return tag expects at least 1 argument");
            return null;
        }
        $type = $this->typeFactory->fromDocumentationString($arguments[0], $ctx);
        $description = implode(" ", array_slice($arguments, 1));
        return new ReturnTag($type, $description);
    }

    public function injectTypeFactory(TypeFactory $t)
    {
        $this->typeFactory = $t;
    }
}
