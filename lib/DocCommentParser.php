<?php
namespace PDoc;

use PDoc\Tags\TagBuilder;

class DocCommentParser
{
    public function parse(string $text)
    {
        $m = [];
        $text = substr($text, 3, -2);
        $text = preg_replace("/^\s*\*\s?/m", "", $text);
        $text = trim($text) . "\n";
        preg_match("/^((([^@][^\n]*)?\n)*)(@.+\n)*$/s", $text, $m);

        if (empty($m)) {
            return new DocBlock('', '', []);
        }

        $description = $m[1] ?? '';
        $tagsStr = $m[4] ?? '';

        list($shortDescription, $longDescription) = $this->parseDescription($description);
        $tags = $this->parseTags($tagsStr);

        return new DocBlock($shortDescription, $longDescription, $tags);
    }
    public function parseDescription(string $description)
    {
        $descriptionLines = explode("\n", $description);
        $shortDescription = $descriptionLines[0];
        $longDescription = join("\n", array_slice($descriptionLines, 1));

        return [$shortDescription, $longDescription];
    }
    public function parseTags(string $tagsStr)
    {
        $tagLines = explode("\n", $tagsStr);
        $tags = [];
        $tagBuilder = new TagBuilder();
        foreach ($tagLines as $tagLine) {
            if (empty($tagLine)) {
                continue;
            }
            $words = preg_split("/\s+/", $tagLine);
            $tagName = substr($words[0], 1);
            $arguments = array_slice($words, 1);
            $tags[] = $tagBuilder->build($tagName, $arguments);
        }
        return $tags;
    }
}
