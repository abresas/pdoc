<?php
namespace PDoc;

class ASTParser
{
    const AST_VERSION = 50;
    public function parseFile($filePath)
    {
        return \ast\parse_file($filePath, self::AST_VERSION);
    }
}
