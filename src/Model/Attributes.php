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
        foreach ($attributes as $key => $value) {
            if (!is_string($key)) {
                throw AttributeCannotBeCreated::becauseSourceArrayIsNotAssociative();
            }

            $attributes[] = Attribute::fromNameAndValue($key, $value);
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
     * @param string $attributeName
     * @return bool
     */
    public function hasAttribute(string $attributeName): bool
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
        if (!isset($this->attributes[$attributeName])) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeDoesNotExist('getAttribute', $attributeName);
        }

        return $this->attributes[$attributeName];
    }

    /**
     * @param Attribute $newAttribute
     * @return Attributes
     */
    public function withNewAttribute(Attribute $newAttribute): Attributes
    {
        if (isset($this->attributes[$newAttribute->getName()])) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeAlreadyExists('withNewAttribute', $newAttribute->getName());
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
        if (!isset($this->attributes[$replacedAttribute->getName()])) {
            throw AttributesOperationIsNotPermitted::
                becauseAttributeDoesNotExist('withReplacedAttribute', $replacedAttribute->getName());
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
        if (!isset($this->attributes[$removedAttribute->getName()])) {
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
}