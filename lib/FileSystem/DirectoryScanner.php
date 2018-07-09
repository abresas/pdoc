<?php
namespace PDoc\FileSystem;

class DirectoryScanner
{
    public function scan($dirPath, $pattern)
    {
        $dir = new \RecursiveDirectoryIterator($dirPath);
        $ite = new \RecursiveIteratorIterator($dir);
        $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
        $fileList = [];
        foreach ($files as $file) {
            var_dump('file', $file);
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }
}
