<?php

require __DIR__ . '/../vendor/autoload.php';

use PDoc\DocumentationGenerator;
use PDoc\DocumentationWriter;

$dirPath = '/home/abresas/dev/pdoc/lib';
if (count($argv) < 2) {
    echo "Usage: " . $argv[0] . " SOURCE_DIR\n";
    exit(1);
}
$dirPath = realpath($argv[1]);
$generator = new DocumentationGenerator();
$namespaces = $generator->parseDirectory($dirPath);
$writer = new DocumentationWriter();
$writer->write($namespaces);
