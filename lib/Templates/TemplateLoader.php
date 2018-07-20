<?php
namespace PDoc\Templates;

use \Aptoma\Twig\Extension\MarkdownEngine;
use \Aptoma\Twig\Extension\MarkdownExtension;

/**
 * Load templates.
 *
 * There is directory for templates that stores all templates as subdirectories.
 * Each template directory has all files necessary for generating the HTML
 * for documentation.:
 *
 * Example file structure:
 *
 * * templates
 *     * light
 *         * class.html
 *         * sidebar.html
 *         * index.html
 *         * style.css
 *     * dark
 *         * class.html
 *         * sidebar.html
 *         * index.html
 *         * style.css
 */
class TemplateLoader
{
    /** @var string $templatesDir Path to templates.
    private $templatesDir;
    /** @var string $templateName Chosen template.
    private $templateName;
    /** @var Twig_Environment $twig; */
    private $twig;

    /**
     * @param string $templatesDir Path to templates.
     * @param string $templateName Chosen template.
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
     * Load template stylesheet (style.css)
     * @return string
     */
    public function loadCSS(): string
    {
        return file_get_contents($this->templatesDir . DIRECTORY_SEPARATOR . $this->templateName . DIRECTORY_SEPARATOR . 'style.css');
    }

    /**
     * Load twig template for class definitions.
     * @return Template
     */
    public function loadClassTemplate()
    {
        return $this->twig->load('class.html');
    }

    /**
     * Load sidebar class list template.
     * @return Template
     */
    public function loadSidebarTemplate()
    {
        return $this->twig->load('sidebar.html');
    }

    /**
     * Load index file template
     * @return Template
     */
    public function loadIndexTemplate()
    {
        return $this->twig->load('index.html');
    }
}
