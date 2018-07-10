<?php
namespace PDoc;

use \PDoc\FileSystem\FileWriter;
use \PDoc\Render\TemplateLoader;

class DocumentationWriter
{
    public function __construct()
    {
        $templateLoader = new TemplateLoader('./templates', 'default');
        $this->classTemplate = $templateLoader->loadClassTemplate();
        $this->fileWriter = new FileWriter();
    }
    public function write(array $namespaces)
    {
        $classList = [];
        $path = realpath('./docs');
        foreach ($namespaces as $namespace) {
            // echo json_encode($namespace->classes[0], \JSON_PRETTY_PRINT, 20);
            foreach ($namespace->classes as $class) {
                $output = $this->classTemplate->render([ 'class' => $class, 'base' => $path]);
                $fileName = str_replace('\\', '.', $namespace->name) . '.' . $class->name . '.html';
                $classList[$fileName] = $class;
                $this->fileWriter->writeFile($path . DIRECTORY_SEPARATOR . $fileName, $output);
            }
        }
        $this->fileWriter->writeFile('./docs/classlist.js', 'window.classList = ' . json_encode($classList));
    }
    public function injectClassTemplate($classTemplate)
    {
        $this->classTemplate = $classTemplate;
    }
    public function injectFileWriter($fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }
}
