<?php
namespace PDoc\Tags;

use PDoc\ParseContext;
use PDoc\SourceLocation;

class TagBuilder
{
    public function build(string $name, array $arguments, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        // this could be implemented as a lookup in name => class array
        // but that would hinder static analysis tools.
        if ($name === 'param') {
            $description = implode(" ", array_slice($arguments, 2));
            return new ParamTag($arguments[0], substr($arguments[1], 1), $description);
        } elseif ($name === 'var') {
            $description = implode(" ", array_slice($arguments, 2));
            if (count($arguments) < 2) {
                error_log($sourceLoc . ":@var tag expects at least 2 arguments");
            }
            $name = count($arguments) > 1 ? substr($arguments[1], 1) : null;
            return new VarTag($arguments[0], $name, $description);
        } elseif ($name === 'return') {
            $description = implode(" ", array_slice($arguments, 1));
            return new ReturnTag($arguments[0], $description);
        } elseif ($name === 'suppress') {
            return null; // not a documentation tag
        } else {
            error_log($sourceLoc . ': Unsupported tag @' . $name);
            throw new \Exception('Invalid tag');
        }
    }
}
