<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;

use \PDoc\Tags\ParamTag;
use \PDoc\Tags\ReturnTag;
use \PDoc\Tags\VarTag;

abstract class AbstractEntity
{
    protected $name;
    protected $type;
    protected $sourceLocation;
    protected $docBlock;
    public function __construct(string $type, string $name, SourceLocation $sourceLoc, DocBlock $docBlock)
    {
        $this->name = $name;
        $this->type = $type;
        $this->sourceLocation = $sourceLoc;
        $this->setDocBlock($docBlock);
    }
    public function setDocBlock(DocBlock $docBlock)
    {
        $this->docBlock = $docBlock;
        foreach ($docBlock->getTags() as $tag) {
            $tag->handledBy($this);
        }
    }
    public function handleParamTag(ParamTag $paramTag)
    {
        error_log($this->sourceLocation . ': Unexpected @param tag on ' . $this->type . ' "' . $this->name . '".');
    }
    public function handleVarTag(VarTag $varTag)
    {
        error_log($this->sourceLocation . ': Unexpected @var tag on ' . $this->type . ' "' . $this->name . '".');
    }
    public function handleReturnTag(ReturnTag $returnTag)
    {
        error_log($this->sourceLocation . ': Unexpected @return tag on ' . $this->type . ' "' . $this->name . '".');
    }
}
