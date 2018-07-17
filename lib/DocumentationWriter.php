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
    /** @var string $templatesPath The directory were templates are stored. */
    private $templatesPath;
    /** @var TemplateLoader $templateLoader Class that loads template files */
    private $templateLoader;
    /** @var FileWriter $fileWriter The object used for writing text files to disk. */
    private $fileWriter;
    public function __construct(string $templatesPath = './templates')
    {
        $this->templatesPath = realpath($templatesPath);
        $this->templateLoader = new TemplateLoader($this->templatesPath, 'default');
        $this->fileWriter = new FileWriter();
    }
    /**
     * Take the generated documentation and write it to disk.
     *
     * Old files in the directory will be overwritten.
     * @param PHPNameSpace[] $namespaces The generated namespaces (with all their classes).
     * @param string $targetPath The directory to write to.
     */
    public function write(iterable $namespaces, string $targetPath = './docs'): void
    {
        $classTemplate = $this->templateLoader->loadClassTemplate();
        $path = realpath($targetPath);
        $this->renderCSS($path);
        $sidebarHtml = $this->renderSidebar($namespaces);
        $this->renderIndex($namespaces, $sidebarHtml, $path);
        foreach ($namespaces as $namespace) {
            foreach ($namespace->classes as $class) {
                $output = $classTemplate->render([ 'class' => $class, 'base' => $path, 'sidebarHtml' => $sidebarHtml ]);
                $fileName = str_replace('\\', '.', $namespace->name) . '.' . $class->name . '.html';
                $this->fileWriter->writeFile($path . DIRECTORY_SEPARATOR . $fileName, $output);
            }
        }
    }
    private function renderCSS(string $path)
    {
        $this->fileWriter->writeFile($path . DIRECTORY_SEPARATOR . "style.css", $this->templateLoader->loadCSS());
    }
    private function renderIndex(iterable $namespaces, string $sidebarHtml, string $path)
    {
        $indexTemplate = $this->templateLoader->loadIndexTemplate();
        $output = $indexTemplate->render(['namespaces' => $namespaces, 'sidebarHtml' => $sidebarHtml]);
        $this->fileWriter->writeFile($path . DIRECTORY_SEPARATOR . 'index.html', $output);
    }
    /**
     * Write common sidebar HTML to a file to be reused in all templates.
     *
     * This avoids having to pass through all classes to generate HTML for every class,
     * just generate the HTML once and then include it in every class file.
     */
    private function renderSidebar(iterable $namespaces): string
    {
        $sidebarTemplate = $this->templateLoader->loadSidebarTemplate();
        return $sidebarTemplate->render([ 'namespaces' => $namespaces ]);
    }
    public function injectTemplateLoader($templateLoader): void
    {
        $this->templateLoader = $templateLoader;
    }
    public function injectFileWriter($fileWriter): void
    {
        $this->fileWriter = $fileWriter;
    }
}
