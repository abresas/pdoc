<?php
namespace PDoc\FileSystem;

/**
 * Write files to disk
 */
class FileWriter
{
    /**
     * Write (replacing if already existing) text to a file.
     * @param string $path Full path to the file to write.
     * @param string $contents The text to write to the file.
     */
    public function writeFile(string $path, string $contents)
    {
        file_put_contents($path, $contents);
    }
}
