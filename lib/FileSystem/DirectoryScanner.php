<?php
namespace PDoc\FileSystem;

/**
 * Scan a directory to produce a list of files.
 */
class DirectoryScanner
{
    /**
     * Scan a directory and filter files to match a certain regex pattern
     * @param string $dirPath Full path to the directory to be scanned.
     * @param string $pattern The regex pattern, ie /^.*\.php$/
     * @return string[] List of full paths to files.
     */
    public function scan(string $dirPath, string $pattern)
    {
        $dir = new \RecursiveDirectoryIterator($dirPath);
        $ite = new \RecursiveIteratorIterator($dir);
        $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
        $fileList = [];
        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }
}
