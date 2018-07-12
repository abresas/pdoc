<?php
namespace PDoc;

use \PDoc\FileSystem\FileWriter;
use \PDoc\Render\TemplateLoader;

/**
 * Write documentation to a target directory.
 *
 * This class takes the documentation generated from a source directory
 * and writes it to HTML/js files to a target directory.
 */
class DocumentationWriter
{
    /** @var object $classTemplate A twig template for classes. */
    private $classTemplate;
    /** @var FileWriter $fileWriter The object used for writing text files to disk. */
    private $fileWriter;
    public function __construct()
    {
        $templateLoader = new TemplateLoader('./templates', 'default');
        $this->classTemplate = $templateLoader->loadClassTemplate();
        $this->fileWriter = new FileWriter();
    }
    /**
     * Take the generated documentation and write it to disk.
     *
     * Old files in the directory will be overwritten.
     * @param PHPNameSpace[] $namespaces The generated namespaces (with all their classes).
     * @param string $targetPath The directory to write to.
     */
    public function write(array $namespaces, $targetPath = './docs'): void
    {
        $classList = [];
        $path = realpath($targetPath);
        foreach ($namespaces as $namespace) {
            $classList[$namespace->name] = [];
            // echo json_encode($namespace->classes[0], \JSON_PRETTY_PRINT, 20);
            foreach ($namespace->classes as $class) {
                $output = $this->classTemplate->render([ 'class' => $class, 'base' => $path]);
                $fileName = str_replace('\\', '.', $namespace->name) . '.' . $class->name . '.html';
                $classList[$namespace->name][$fileName] = $class;
                $this->fileWriter->writeFile($path . DIRECTORY_SEPARATOR . $fileName, $output);
            }
        }
        $this->fileWriter->writeFile('./docs/classlist.js', 'window.classList = ' . json_encode($classList));
    }
    public function injectClassTemplate($classTemplate): void
    {
        $this->classTemplate = $classTemplate;
    }
    public function injectFileWriter($fileWriter): void
    {
        $this->fileWriter = $fileWriter;
    }
}
