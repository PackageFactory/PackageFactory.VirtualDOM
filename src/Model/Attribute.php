<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

use PackageFactory\VirtualDOM\Util\Stringifier;

final class Attribute
{
    /**
     * @var AttributeName
     */
    private $name;

    /**
     * @var string|boolean
     */
    private $value;

    /**
     * @param AttributeName $name
     * @param string|boolean $value
     * @throws InvariantException
     */
    private function __construct(
        AttributeName $name,
        $value
    ) {
        if (!is_string($value) && !is_bool($value)) {
            throw AttributeIsInvalid::
                becauseItsValueIsNeitherOfTypeStringNorBoolean((string) $name, $value);
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

        return new self(AttributeName::fromString($name), $value);
    }

    /**
     * @return AttributeName
     */
    public function getName(): AttributeName
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isBoolean(): bool
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
        if ($this->isBoolean()) {
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