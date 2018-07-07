<?php
namespace PDoc;

class SourceLocation implements \JsonSerializable
{
    public $filePath;
    public $line;
    public function __construct(string $filePath, int $line)
    {
        $this->filePath = $filePath;
        $this->line = $line;
    }
    public function jsonSerialize(): array
    {
        return [
            'filePath' => $this->filePath,
            'line' => $this->line
        ];
    }
    public function __toString(): string
    {
        return $this->filePath . ':' . $this->line;
    }
}
