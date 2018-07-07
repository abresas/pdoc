<?php
namespace PDoc\Entities;

use \PDoc\DocBlock;
use \PDoc\SourceLocation;
use \PDoc\Tags\ParamTag;
use \PDoc\Tags\ReturnTag;

class PHPMethod extends AbstractEntity implements \JsonSerializable
{
    /** @var PHPParameter[] $parameters */
    public $parameters = [];
    public $returnType = 'any';
    public $returnDescription = '';

    public function __construct(string $name, SourceLocation $loc, DocBlock $docBlock, $parameters = [])
    {
        foreach ($parameters as $parameter) {
            $this->parameters[$parameter->name] = $parameter;
        }
        parent::__construct('method', $name, $loc, $docBlock);
    }
    public function handleParamTag(ParamTag $paramTag)
    {
        if (!isset($this->parameters[$paramTag->variable])) {
            throw new \Exception('Found @param tag for ' . $paramTag->variable . ', but there is no such parameter.');
        }
        $param = $this->parameters[$paramTag->variable];
        $param->type = $paramTag->type;
        $param->description = $paramTag->description;
    }
    public function handleReturnTag(ReturnTag $returnTag)
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
