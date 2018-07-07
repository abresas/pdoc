<?php
namespace PDoc\Tags;

class TagBuilder
{
    public function build(string $name, array $arguments)
    {
        // this could be implemented as a lookup in name => class array
        // but that would hinder static analysis tools.
        if ($name === 'param') {
            $description = implode(" ", array_slice($arguments, 2));
            return new ParamTag($arguments[0], substr($arguments[1], 1), $description);
        } elseif ($name === 'var') {
            $description = implode(" ", array_slice($arguments, 2));
            return new VarTag($arguments[0], substr($arguments[1], 1), $description);
        } elseif ($name === 'return') {
            $description = implode(" ", array_slice($arguments, 1));
            return new ReturnTag($arguments[0], $description);
        } else {
            throw new \Exception('Invalid tag ' . $name . ' encountered');
        }
    }
}
