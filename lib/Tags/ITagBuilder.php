<?php
namespace PDoc\Tags;

use \PDoc\ParseContext;
use \PDoc\SourceLocation;

interface ITagBuilder
{
    public function build(string $name, array $arguments, ParseContext $ctx, SourceLocation $loc): ?AbstractTag;
}
