<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class DangerouslyUnescapedText extends Node
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
        $this->value = $value;
    }

    /**
     * @param string $data
     * @return self
     */
    public static function createFromString(string $data): self
    {
        return new self($data);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}