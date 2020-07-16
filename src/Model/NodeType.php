<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class NodeType
{
    /**
     * Representation of an HTML Element
     */
    const ELEMENT = 'ELEMENT';

    /**
     * A List of HTML Elements without wrapping
     */
    const FRAGMENT = 'FRAGMENT';

    /**
     * Simple string content
     */
    const TEXT = 'TEXT';

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        if (!in_array($value, self::getValues())) {
            throw NodeTypeIsInvalid::becauseItIsNotOneOfThePredefinedValues($value);
        }

        $this->value = $value;
    }

    /**
     * @param string $string
     * @return self
     */
    public static function fromString(string $string): self
    {
        return new self($string);
    }

    /**
     * @return self
     */
    public static function element(): self
    {
        return new self(self::ELEMENT);
    }

    /**
     * @return self
     */
    public static function fragment(): self
    {
        return new self(self::FRAGMENT);
    }

    /**
     * @return self
     */
    public static function text(): self
    {
        return new self(self::TEXT);
    }

    /**
     * @return boolean
     */
    public function isElement(): bool
    {
        return $this->value === self::ELEMENT;
    }

    /**
     * @return boolean
     */
    public function isFragment(): bool
    {
        return $this->value === self::FRAGMENT;
    }

    /**
     * @return boolean
     */
    public function isText(): bool
    {
        return $this->value === self::TEXT;
    }

    /**
     * @return array
     */
    public static function getValues(): array
    {
        return [
            self::ELEMENT,
            self::FRAGMENT,
            self::TEXT,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}