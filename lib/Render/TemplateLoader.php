<?php
namespace PDoc\Render;

use \Aptoma\Twig\Extension\MarkdownEngine;
use \Aptoma\Twig\Extension\MarkdownExtension;

class TemplateLoader
{
    private $templatesDir;
    private $templateName;
    private $twig;
    public function __construct(string $templatesDir, string $templateName)
    {
        $this->templatesDir = $templatesDir;
        $this->templateName = $templateName;
        $loader = new \Twig_Loader_Filesystem($templatesDir . DIRECTORY_SEPARATOR . $templateName);
        $engine = new MarkdownEngine\MichelfMarkdownEngine();
        $this->twig = new \Twig_Environment($loader);
        $this->twig->addExtension(new MarkdownExtension($engine));
    }
    public function loadCSS()
    {
        return file_get_contents($this->templatesDir . DIRECTORY_SEPARATOR . $this->templateName . DIRECTORY_SEPARATOR . 'style.css');
    }
    public function loadClassTemplate()
    {
        return $this->twig->load('class.html');
    }
    public function loadSidebarTemplate()
    {
        return $this->twig->load('sidebar.html');
    }
    public function loadIndexTemplate()
    {
        return $this->twig->load('index.html');
    }
}
