<?php
namespace PDoc;

/**
 * Location of a symbol in the project's source code.
 *
 * Corresponds to a pair (filePath, line) with a handy
 * jsonSerialize and __toString implementations.
 */
class SourceLocation implements \JsonSerializable
{
    /** @var string $filePath Path to file. */
    public $filePath;
    /** @var int $line Line within the file. */
    public $line;
    /**
     * @param string $filePath Path to file.
     * @param int $line Line within the file.
     */
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
