<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\ParamTag;
use \PDoc\Tags\ReturnTag;
use \PDoc\Types\AbstractType;

/**
 * A method of a class.
 *
 * Methods have description, return type and description, parameters, and
 * modifiers: visibility (public, protected, private), "static", "abstract", "final".
 */
class PHPMethod extends AbstractEntity implements \JsonSerializable
{
    /** @var AbstractType $returnType */
    public $returnType;
    /** @var PHPParameter[] $parameters */
    public $parameters = [];
    /** @var string $returnDescription */
    public $returnDescription = '';
    /** @var string $visibility */
    public $visibility;
    /** @var bool $isStatic */
    public $isStatic;
    /** @var bool $isAbstract */
    public $isAbstract;
    /** @var bool $isFinal */
    public $isFinal;

    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock, AbstractType $returnType, string $visibility, bool $isStatic, bool $isAbstract, bool $isFinal, array $parameters = [])
    {
        foreach ($parameters as $parameter) {
            $this->parameters[$parameter->name] = $parameter;
        }
        $this->returnType = $returnType;
        $this->visibility = $visibility;
        $this->isStatic = $isStatic;
        $this->isAbstract = $isAbstract;
        $this->isFinal = $isFinal;
        parent::__construct('method', $name, $loc, $docBlock);
    }
    /**
     * Change a parameter's attributes based on an _param_ tag found on this method's documentation.
     * @param ParamTag $paramTag The _param_ tag that was found.
     */
    public function handleParamTag(ParamTag $paramTag): void
    {
        if (!isset($this->parameters[$paramTag->variable])) {
            error_log($this->sourceLocation . ': Found @param tag for ' . $paramTag->variable . ', but there is no such parameter.');
            return;
        }
        $param = $this->parameters[$paramTag->variable];
        $param->type = $paramTag->type;
        $param->description = $paramTag->description;
    }
    /**
     * Change this method's attributes based on the _return_ tag found on its documentation.
     * @param ReturnTag $returnTag The return tag to use for determining return type and description.
     */
    public function handleReturnTag(ReturnTag $returnTag): void
    {
        $this->returnType = $returnTag->type;
        $this->returnDescription = $returnTag->description;
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'parameters' => $this->parameters,
            'returnType' => $this->returnType,
            'returnDescription' => $this->returnDescription
        ];
    }
}
