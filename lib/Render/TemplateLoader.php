<?php
namespace PDoc\Render;

use \Aptoma\Twig\Extension\MarkdownEngine;
use \Aptoma\Twig\Extension\MarkdownExtension;

class TemplateLoader
{
    private $templatesDir;
    private $templateName;
    private $twig;
    /**
     * @suppress PhanUndeclaredClassMethod
     */
    public function __construct(string $templatesDir, string $templateName)
    {
        $this->templatesDir = $templatesDir;
        $this->templateName = $templateName;
        $loader = new \Twig_Loader_Filesystem($templatesDir . DIRECTORY_SEPARATOR . $templateName);
        $engine = new MarkdownEngine\MichelfMarkdownEngine();
        $this->twig = new \Twig_Environment($loader);
        $this->twig->addExtension(new MarkdownExtension($engine));
    }
    /**
     * @suppress PhanUndeclaredClassMethod
     */
    public function loadClassTemplate()
    {
        return $this->twig->load('class.html');
    }
}
