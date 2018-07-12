<?php
namespace PDoc;

use PDoc\Tags\TagBuilder;

/**
 * Parse a phpDoc comment.
 */
class DocCommentParser
{
    /**
     * @param string $text The doc comment text.
     * @param ParseContext $ctx The context (known symbols etc) of the parser so far.
     * @param SourceLoc $sourceLoc The location where the comment was found.
     * @return DocBlock
     */
    public function parse(string $text, ParseContext $ctx, SourceLocation $sourceLoc): DocBlock
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
    /**
     * Parse the description part of the comment.
     *
     * We identify description as the text before tags. Any text after or between
     * lines starting with a tag is ignored.
     * @param string $description The part of the comment before any tags.
     * @return string[] Two strings, the short and long description.
     */
    public function parseDescription(string $description): array
    {
        $descriptionLines = explode("\n", $description);
        $shortDescription = $descriptionLines[0];
        $longDescription = join("\n", array_slice($descriptionLines, 1));

        return [$shortDescription, $longDescription];
    }
    /**
     * Parse tags in a phpDoc comment.
     * @param string $tagsStr The string containing all the lines with tags.
     * @param ParseContext $ctx The context of the parser for shared memory.
     * @param SourceLocation $sourceLoc The location in the source code where the tags were found.
     * @return \PDoc\Tags\AbstractTag[]
     */
    public function parseTags(string $tagsStr, ParseContext $ctx, SourceLocation $sourceLoc): array
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
