<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class Attributes implements \IteratorAggregate, \Countable
{
    /**
     * @var array|Attribute[]
     */
    private $attributes;

    /**
     * @param array|Attribute[] $attributes
     */
    private function __construct(array $attributes) 
    {
        $this->attributes = $attributes;
    }

    /**
     * @param array $attributes 
     * @return self
     * @throws InvariantException
     */
    public static function fromArray(array $array): self
    {
        $attributes = [];
        foreach ($array as $name => $value) {
            if (!is_string($name)) {
                throw AttributeCannotBeCreated::becauseSourceArrayIsNotAssociative();
            }

            if ($value instanceof Attribute) {
                $attributes[$name] = $value;
            } else {
                $attributes[$name] = Attribute::fromNameAndValue($name, $value);
            }
        }

        return new self($attributes);
    }

    /**
     * @return array|Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param AttributeName $attributeName
     * @return bool
     */
    public function hasAttribute(AttributeName $attributeName): bool
    {
        return isset($this->attributes[(string) $attributeName]);
    }

    /**
     * @param AttributeName $attributeName
     * @return Attribute
     * @throws InvariantException
     */
    public function getAttribute(AttributeName $attributeName): Attribute
    {
        if (!$this->hasAttribute($attributeName)) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeDoesNotExist('getAttribute', (string) $attributeName);
        }

        return $this->attributes[(string) $attributeName];
    }

    /**
     * @param Attribute $newAttribute
     * @return Attributes
     */
    public function withNewAttribute(Attribute $newAttribute): Attributes
    {
        if ($this->hasAttribute($newAttribute->getName())) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeAlreadyExists('withNewAttribute', (string) $newAttribute->getName());
        }

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
        if (!$this->hasAttribute($appendedAttribute->getName())) {
            return $this->withNewAttribute($appendedAttribute);
        } else {
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
        if (!$this->hasAttribute($replacedAttribute->getName())) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeDoesNotExist('withReplacedAttribute', (string) $replacedAttribute->getName());
        }

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
        if (!$this->hasAttribute($removedAttribute->getName())) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeDoesNotExist('withRemovedAttribute', $removedAttribute->getName());
        }

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
     * @return Attributes
     */
    public function withShallowlyMergedAttributes(Attributes $other): Attributes
    {
        return new self(array_replace($this->attributes, iterator_to_array($other)));
    }

    /**
     * @param Attributes $other
     * @return Attributes
     */
    public function withDeeplyMergedAttributes(Attributes $other): Attributes
    {
        $attributes = $this->attributes;

        foreach ($other as $attribute) {
            if ($this->hasAttribute($attribute->getName()) && !$attribute->isBoolean()) {
                $attributes[(string) $attribute->getName()] = $this
                    ->getAttribute($attribute->getName())
                    ->withAppendedValue($attribute->getValue());
            } else {
                $attributes[(string) $attribute->getName()] = $attribute;
            }
        }

        return new self($attributes);
    }

    /**
     * @param Attributes $other
     * @return boolean
     */
    public function equals(Attributes $other): bool
    {
        foreach ($other->getAttributes() as $attribute) {
            if ($this->hasAttribute($attribute->getName())) {
                if (!$attribute->equals($this->getAttribute($attribute->getName()))) {
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
}