<?php

require __DIR__ . '/../vendor/autoload.php';

use PDoc\DocumentationGenerator;
use PDoc\DocumentationWriter;
use PDoc\FileSystem\DirectoryScanner;

$dirPath = '/home/abresas/dev/pdoc/lib';
if (count($argv) < 2) {
    echo "Usage: " . $argv[0] . " SOURCE_DIR\n";
    exit(1);
}
$dirPath = realpath($argv[1]);
$dirScanner = new DirectoryScanner();
$files = $dirScanner->scan($dirPath, '/^.*\.php$/');
$generator = new DocumentationGenerator();
$documentation = $generator->parseFiles($files);
$writer = new DocumentationWriter();
$writer->write($documentation);
