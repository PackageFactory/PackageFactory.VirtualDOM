<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Text extends Node
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct(
        string $value
    ) {
        $this->value = Escaper::escapeTextNodeValue($value);
    }

    /**
     * @param string $data
     * @return self
     */
    public static function fromString(string $data): self
    {
        return new self($data);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}