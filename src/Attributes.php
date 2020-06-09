<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Attributes implements \IteratorAggregate, \Countable
{
    /**
     * @var array|Attribute[]
     */
    private $attributes;

    /**
     * @param Attribute ...$attributes
     * @throws InvariantException
     */
    public function __construct(
        Attribute ...$attributes
    ) {
        $this->attributes = [];

        foreach ($attributes as $attribute) {
            Invariant::check(
                !isset($this->attributes[$attribute->getName()]),
                sprintf(
                    'Attribute name "%s" must not be used more than once',
                    $attribute->getName()
                )
            );

            $this->attributes[$attribute->getName()] = $attribute;
        }
    }

    /**
     * @return self
     */
    public static function createEmpty(): self
    {
        return new self();
    }

    /**
     * @param array<string, string|bool> $data 
     * @return self
     * @throws InvariantException
     */
    public static function createFromArray(array $data): self
    {
        /** @var array|Attribute[] $attributes */
        $attributes = [];
        foreach ($data as $name => $value) {
            Invariant::check(
                is_string($value) || is_bool($value),
                sprintf(
                    'Value for attribute with name "%s" must either be string or boolean. ' .
                    'Got "%s" instead.',
                    $name,
                    gettype($value)
                )
            );

            if (is_string($value)) {
                $attributes[] = Attribute::createFromNameAndValue($name, $value);
            }
            else if ($value) {
                $attributes[] = Attribute::createBooleanFromName($name);
            }
        }

        return new self(...$attributes);
    }

    /**
     * @return array|Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $attributeName
     * @return bool
     */
    public function getHasAttribute(string $attributeName): bool
    {
        return isset($this->attributes[$attributeName]);
    }

    /**
     * @param string $attributeName
     * @return Attribute
     * @throws InvariantException
     */
    public function getAttribute(string $attributeName): Attribute
    {
        Invariant::check(
            isset($this->attributes[$attributeName]),
            sprintf(
                'Attribute with name "%s" does not exist',
                $attributeName
            )
        );

        return $this->attributes[$attributeName];
    }

    /**
     * @param Attribute $newAttribute
     * @return Attributes
     */
    public function withNewAttribute(Attribute $newAttribute): Attributes
    {
        Invariant::check(
            !isset($this->attributes[$newAttribute->getName()]),
            sprintf(
                'New attribute with name "%s" must not already exist',
                $newAttribute->getName()
            )
        );

        $nextAttributes = [];
        foreach ($this->attributes as $attribute) {
            $nextAttributes[] = $attribute;
        }
        $nextAttributes[] = $newAttribute;

        return new self(...$nextAttributes);
    }

    /**
     * @param Attribute $attribute
     * @return Attributes
     */
    public function withAppendedAttribute(Attribute $appendedAttribute): Attributes
    {
        if (!isset($this->attributes[$appendedAttribute->getName()])) {
            return $this->withNewAttribute($appendedAttribute);
        }
        else {
            $nextAttributes = [];
            foreach ($this->attributes as $attribute) {
                if ($attribute->getName() === $appendedAttribute->getName()) {
                    $nextAttributes[] = $attribute->withAppendedAttribute($appendedAttribute);
                }
                else {
                    $nextAttributes[] = $attribute;
                }
            }

            return new self(...$nextAttributes);
        }
    }

    /**
     * @param Attribute $attribute
     * @return Attributes
     */
    public function withReplacedAttribute(Attribute $replacedAttribute): Attributes
    {
        Invariant::check(
            isset($this->attributes[$replacedAttribute->getName()]),
            sprintf(
                'Replaced attribute with name "%s" must exist',
                $replacedAttribute->getName()
            )
        );

        $nextAttributes = [];
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $replacedAttribute->getName()) {
                $nextAttributes[] = $replacedAttribute;
            }
            else {
                $nextAttributes[] = $attribute;
            }
        }

        return new self(...$nextAttributes);
    }

    /**
     * @param Attribute $removedAttribute
     * @return Attributes
     */
    public function withRemovedAttribute(Attribute $removedAttribute): Attributes
    {
        Invariant::check(
            isset($this->attributes[$removedAttribute->getName()]),
            sprintf(
                'Removed attribute with name "%s" must exist',
                $removedAttribute->getName()
            )
        );

        $nextAttributes = [];
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() !== $removedAttribute->getName()) {
                $nextAttributes[] = $attribute;
            }
        }

        return new self(...$nextAttributes);
    }

    /**
     * @param Attributes $other
     * @return boolean
     */
    public function equals(Attributes $other): bool
    {
        foreach ($other->getAttributes() as $attribute) {
            if (isset($this->attributes[$attribute->getName()])) {
                if (!$attribute->equals($this->attributes[$attribute->getName()])) {
                    return false;
                }
            }
            else {
                return false;
            }
        }

        return true;
    }

    /**
     * @return iterable|Attribute[]
     */
    public function getIterator(): iterable
    {
        foreach ($this->attributes as $attribute) {
            yield $attribute->getName() => $attribute;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->count() === 0) {
            return '';
        } else {
            return implode('', $this->attributes);
        }
    }
}