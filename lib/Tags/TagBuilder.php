<?php
namespace PDoc\Tags;

use PDoc\ParseContext;
use PDoc\SourceLocation;
use PDoc\Types\ClassType;
use PDoc\Types\TypeFactory;

class TagBuilder
{
    /** @var TypeFactory $typeFactory */
    private $typeFactory;
    public function __construct()
    {
        $this->typeFactory = new TypeFactory();
    }
    public function build(string $name, array $arguments, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        // this could be implemented as a lookup in name => class array
        // but that would hinder static analysis tools.
        if ($name === 'param') {
            if (count($arguments) < 2) {
                error_log($sourceLoc . ": @param tag expects at least 2 arguments");
                return null;
            }
            $description = implode(" ", array_slice($arguments, 2));
            $type = $this->typeFactory->fromDocumentationString($arguments[0], $ctx);
            return new ParamTag($type, substr($arguments[1], 1), $description);
        } elseif ($name === 'var') {
            $description = implode(" ", array_slice($arguments, 2));
            if (count($arguments) < 2) {
                error_log($sourceLoc . ": @var tag expects at least 2 arguments");
                return null;
            }
            $name = substr($arguments[1], 1);
            $type = $this->typeFactory->fromDocumentationString($arguments[0], $ctx);
            return new VarTag($type, $name, $description);
        } elseif ($name === 'return') {
            if (empty($arguments)) {
                error_log($sourceLoc . ": @return tag expects at least 1 argument");
                return null;
            }
            $type = $this->typeFactory->fromDocumentationString($arguments[0], $ctx);
            $description = implode(" ", array_slice($arguments, 1));
            return new ReturnTag($type, $description);
        } elseif ($name === 'suppress') {
            return null; // not a documentation tag
        } else {
            error_log($sourceLoc . ': Unsupported tag @' . $name);
            throw new \Exception('Invalid tag');
        }
    }
    public function injectTypeFactory(TypeFactory $t)
    {
        $this->typeFactory = $t;
    }
}
