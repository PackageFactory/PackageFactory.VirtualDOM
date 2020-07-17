<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

use PackageFactory\VirtualDOM\Util\Stringifier;

final class Attribute
{
    /**
     * Based on https://www.w3.org/TR/2011/WD-html5-20110525/syntax.html#attributes-0
     * But more restrictive
     *
     * @var string
     */
    private const PATTERN_ATTRIBUTE_NAME = '/^[a-z][-_a-z0-9]*$/';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|boolean
     */
    private $value;

    /**
     * @param string $name
     * @param string|boolean $value
     * @throws InvariantException
     */
    private function __construct(
        string $name,
        $value
    ) {
        if (!preg_match(self::PATTERN_ATTRIBUTE_NAME, $name)) {
            throw AttributeIsInvalid::
                becauseItsNameDoesNotMatchTheRequiredPattern($name, self::PATTERN_ATTRIBUTE_NAME);
        }

        if (!is_string($value) && !is_bool($value)) {
            throw AttributeIsInvalid::
                becauseItsValueIsNeitherOfTypeStringNorBoolean($name, $value);
        }

        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public static function fromNameAndValue(string $name, $value): self
    {
        if (!is_bool($value)) {
            $value = Stringifier::stringify($value);
        }

        return new self($name, $value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getIsBoolean(): bool
    {
        return is_bool($this->value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withAppendedValue(string $value): self
    {
        if ($this->getIsBoolean()) {
            return new self($this->name, $value);
        } else {
            return new self($this->name, $this->value . ' ' . $value);
        }
    }

    /**
     * @param Attribute $other
     * @return bool
     */
    public function equals(Attribute $other): bool
    {
        return $this->name === $other->getName() && $this->value === $other->getValue();
    }
}