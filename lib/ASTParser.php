<?php
namespace PDoc;

/**
 * Generate AST for a file.
 *
 * Wrapper around \ast\parse_file for unit testing / forwads-compatibility.
 */
class ASTParser
{
    const AST_VERSION = 50;
    /**
     * Generate AST for a file and return the root node.
     * @param string $filePath Path to the file to parse.
     * @return \ast\Node The root node.
     */
    public function parseFile(string $filePath): \ast\Node
    {
        return \ast\parse_file($filePath, self::AST_VERSION);
    }
}
