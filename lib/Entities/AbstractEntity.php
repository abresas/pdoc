<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;

use \PDoc\Tags\ParamTag;
use \PDoc\Tags\ReturnTag;
use \PDoc\Tags\VarTag;

/**
 * Represents any documentable PHP language symbol, such as classes, functions, etc.
 *
 * The child classes hold all attributes that are used for generating documentation for each symbol,
 * while this class has all common attributes such as name (you can't document something anonymous).
 *
 * Each child class defines _handle*Tag_ methods for each type of tag that is supported by that class.
 * If a child class does not define such a method and a tag exists then the respective method of this
 * class gets called and either it is a generic tag that can be applied to all entities, or
 * it logs an error that this tag was unexpected.
 */
abstract class AbstractEntity
{
    /** @var string $name The symbol's name in the source code. */
    public $name;
    /** @var string $entityType A user-friendly name for the current symbol class, eg "function". */
    public $entityType;
    /** @var SourceLocation $sourceLocation The file and line the symbol appeared in. */
    public $sourceLocation;
    /** @var string $shortDescription The first line of description appears in listings etc. */
    public $shortDescription = '';
    /** @var string $longDescription Any description in the phpDoc comment after the short description. */
    public $longDescription = '';
    /** @var DocBlock $docBlock Parsed phpDoc comment. */
    protected $docBlock;

    /**
     * Construct an entity.
     *
     * This constructor is supposed to be overloaded, and then internally called with the first argument
     * being the entityType, instead of having the caller of this class have to pass it manually.
     *
     * @param string $type
     * @param string $name
     * @param SourceLocation $sourceLoc
     * @param DocBlock $docBlock
     */
    public function __construct(string $type, string $name, SourceLocation $sourceLoc, DocBlock $docBlock)
    {
        $this->name = $name;
        $this->entityType = $type;
        $this->sourceLocation = $sourceLoc;
        $this->setDocBlock($docBlock);
    }
    /**
     * Change attributes of this symbol according to documentation.
     * @param DocBlock $docBlock The parsed phpDoc comment block.
     */
    public function setDocBlock(DocBlock $docBlock): void
    {
        $this->docBlock = $docBlock;
        $this->shortDescription = $docBlock->shortDescription;
        $this->longDescription = $docBlock->longDescription;
        foreach ($docBlock->tags as $tag) {
            $tag->handledBy($this);
        }
    }
    /**
     * Log that an unexpected tag was found (ie, a handle*Tag method was not overloaded, and was called).
     * @param string $tag The tag type, including the initial "@" symbol, ie "@param"
     */
    protected function logUnexpectedTag($tag): void
    {
        error_log($this->sourceLocation . ': Unexpected ' . $tag . ' tag on ' . $this->entityType . ' "' . $this->name . '".');
    }
    /**
     * Handle a param tag, logs an error if not overloaded.
     * @param ParamTag $paramTag The @param tag that was found.
     */
    public function handleParamTag(ParamTag $paramTag): void
    {
        $this->logUnexpectedTag('@param');
    }
    /**
     * Handle a var tag, logs an error if not overloaded.
     * @param VarTag $varTag The @var tag.
     */
    public function handleVarTag(VarTag $varTag): void
    {
        $this->logUnexpectedTag('@var');
    }
    /**
     * Handle a return tag, logs an error if not overloaded.
     * @param ReturnTag $returnTag The @return tag.
     */
    public function handleReturnTag(ReturnTag $returnTag): void
    {
        $this->logUnexpectedTag('@return');
    }
}
