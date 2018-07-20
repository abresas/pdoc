<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\ParamTag;
use \PDoc\Tags\ReturnTag;
use \PDoc\Types\AbstractType;

/**
 * Definition of non-class functions (class methods treated as PHPMethod).
 *
 * A function has documentation, some parameters, and return type and description.
 */
class PHPFunction extends AbstractEntity implements \JsonSerializable
{
    /** @var PHPParameter[] $parameters */
    public $parameters = [];
    /** @var AbstractType $returnType */
    public $returnType;
    /** @var string $returnDescription */
    public $returnDescription = '';

    /**
     * @param string $name
     * @param SourceLocation $loc
     * @param DocBlock $docBlock
     * @param PHPParameter[] $parameters
     */
    public function __construct(string $name, SourceLocation $loc, AbstractType $returnType, DocBlock $docBlock, $parameters = [])
    {
        foreach ($parameters as $parameter) {
            $this->parameters[$parameter->name] = $parameter;
        }
        $this->returnType = $returnType;
        parent::__construct('function', $name, $loc, $docBlock);
    }
    /**
     * @param ParamTag $paramTag
     */
    public function handleParamTag(ParamTag $paramTag): void
    {
        if (!isset($this->parameters[$paramTag->variable])) {
            throw new \Exception('Found @param tag for ' . $paramTag->variable . ', but there is no such parameter.');
        }
        $param = $this->parameters[$paramTag->variable];
        $param->type = $paramTag->type;
        $param->description = $paramTag->description;
    }
    /**
     * Set return type and description based on documentation.
     *
     * This discards return types deduced from typehints,
     * or previous calls to this method.
     */
    public function handleReturnTag(ReturnTag $returnTag): void
    {
        $this->returnType = $returnTag->type;
        $this->returnDescription = $returnTag->description;
    }
    /**
     * Implementation of \JsonSerializable interface
     */
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
