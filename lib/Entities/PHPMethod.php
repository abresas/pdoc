<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\ParamTag;
use \PDoc\Tags\ReturnTag;
use \PDoc\Types\AbstractType;

class PHPMethod extends AbstractEntity implements \JsonSerializable
{
    /** @var PHPParameter[] $parameters */
    public $returnType;
    public $parameters = [];
    public $returnDescription = '';

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
    public function handleReturnTag(ReturnTag $returnTag): void
    {
        //$this->returnType = $returnTag->type;
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
