<?php

require __DIR__ . '/../vendor/autoload.php';

use PDoc\DocumentationGenerator;
use PDoc\DocumentationWriter;

$dirPath = '/home/abresas/dev/pdoc/lib';
$generator = new DocumentationGenerator();
$namespaces = $generator->parseDirectory($dirPath);
$writer = new DocumentationWriter();
$writer->write($namespaces);
