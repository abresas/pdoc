<?php
namespace PDoc;

use PDoc\Tags\TagBuilder;

class DocCommentParser
{
    public function parse(string $text, ParseContext $ctx, SourceLocation $sourceLoc)
    {
        $m = [];
        $text = substr($text, 3, -2);
        $text = preg_replace("/^ *\* */m", "", $text);
        $text = trim($text) . "\n";
        preg_match("/^(((\n)|([^@\n]+\n))*)((@[^\n]+\n)*)$/sAD", $text, $m);


        if (empty($m)) {
            return new DocBlock('', '', []);
        }

        $description = $m[1] ?? '';
        $tagsStr = $m[5] ?? '';

        list($shortDescription, $longDescription) = $this->parseDescription($description);
        $tags = $this->parseTags($tagsStr, $ctx, $sourceLoc);

        return new DocBlock($shortDescription, $longDescription, $tags);
    }
    public function parseDescription(string $description)
    {
        $descriptionLines = explode("\n", $description);
        $shortDescription = $descriptionLines[0];
        $longDescription = join("\n", array_slice($descriptionLines, 1));

        return [$shortDescription, $longDescription];
    }
    public function parseTags(string $tagsStr, ParseContext $ctx, $sourceLoc)
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
            try {
                $tag = $tagBuilder->build($tagName, $arguments, $ctx, $sourceLoc);
                if (!is_null($tag)) {
                    $tags[] = $tag;
                }
            } catch (\Exception $e) {
                // TODO: check exception class/message
            }
        }
        return $tags;
    }
}
