<?php
namespace PDoc;

/**
 * A parsed block of phpDoc documentation.
 */
class DocBlock
{
    /** @var string $shortDescription */
    public $shortDescription;
    /** @var string $longDescription */
    public $longDescription;
    /** @var \PDoc\Tags\AbstractTag[] $tags */
    public $tags;

    public function __construct(string $shortDescription, string $longDescription, array $tags)
    {
        $this->shortDescription = $shortDescription;
        $this->longDescription = $longDescription;
        $this->tags = $tags;
    }
}
