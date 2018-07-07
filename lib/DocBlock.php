<?php
namespace PDoc;

class DocBlock
{
    private $shortDescription;
    private $longDescription;
    private $tags;
    public function __construct($shortDescription, $longDescription, $tags)
    {
        $this->shortDescription = $shortDescription;
        $this->longDescription = $longDescription;
        $this->tags = $tags;
    }
    public function getShortDescription()
    {
        return $this->shortDescription;
    }
    public function getLongDescription()
    {
        return $this->longDescription;
    }
    public function getTags()
    {
        return $this->tags;
    }
}
