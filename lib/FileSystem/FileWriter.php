<?php
namespace PDoc\FileSystem;

class FileWriter
{
    public function writeFile(string $path, string $contents)
    {
        file_put_contents($path, $contents);
    }
}
