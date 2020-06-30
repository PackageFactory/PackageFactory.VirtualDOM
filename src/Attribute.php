<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

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
     * @var bool
     */
    private $isBoolean;

    /**
     * @var string|null
     */
    private $value;

    /**
     * @param string $name
     * @param boolean $isBoolean
     * @param string|array|object|null $value
     * @throws InvariantException
     */
    private function __construct(
        string $name,
        bool $isBoolean,
        $value = null
    ) {
        $this->name = mb_strtolower($name);
        $this->isBoolean = $isBoolean;
        $this->value = null;

        Invariant::check(
            (bool) preg_match(self::PATTERN_ATTRIBUTE_NAME, $this->name),
            sprintf(
                'Attribute name "%s" must match pattern "%s"', 
                $this->name,
                self::PATTERN_ATTRIBUTE_NAME
            )
        );

        if ($this->isBoolean) {
            Invariant::check(
                $value === null,
                sprintf('Boolean attribute "%s" must not have a value', $this->name)
            );
        } 
        else {
            Invariant::check(
                $value !== null,
                sprintf('Non-Boolean attribute "%s" must have a value', $this->name)
            );

            Invariant::check(
                is_string($value) || is_array($value) || is_object($value),
                sprintf(
                    'Attribute "%s" value must be of type string, array or object. Got "%".', 
                    $this->name,
                    gettype($value)
                )
            );

            if (is_array($value) || is_object($value)) {
                $concatenatedValue = [];


                foreach ($value as $key => $segment) {
                    if (is_numeric($key) && is_string($segment)) {
                        $concatenatedValue[] = $segment;
                    } elseif (is_string($key) && $segment) {
                        $concatenatedValue[] = $key;
                    }
                }

                $this->value = trim(implode(' ', $concatenatedValue));
            }
            else {
                $this->value = $value;
            }
        }
    }

    /**
     * @param string $name
     * @param string|array $value
     * @return self
     * @throws InvariantException
     */
    public static function createFromNameAndValue(string $name, $value): self
    {
        return new self($name, false, $value);
    }

    /**
     * @param string $name
     * @return self
     * @throws InvariantException
     */
    public static function createBooleanFromName(string $name): self
    {
        return new self($name, true);
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
        return $this->isBoolean;
    }

    /**
     * @return string
     * @throws InvariantException
     */
    public function getValue(): string
    {
        Invariant::check(
            !$this->isBoolean,
            sprintf('Boolean attribute "%s" does not have a value', $this->name)
        );

        return $this->value;
    }

    /**
     * @param Attribute $attribute
     * @return Attribute
     * @throws InvariantException
     */
    public function withAppendedAttribute(Attribute $attribute): Attribute
    {
        Invariant::check(
            $attribute->getName() === $this->name,
            sprintf(
                'Appended attribute "%s" must have the name "%s"', 
                $attribute->getName(),
                $this->name
            )
        );

        if ($attribute->getIsBoolean()) {
            return self::createBooleanFromName(
                $attribute->getName()
            );
        }
        else if ($this->isBoolean) {
            return self::createFromNameAndValue(
                $attribute->getName(), 
                $attribute->getValue()
            );
        }
        else {
            return self::createFromNameAndValue(
                $attribute->getName(), 
                $this->value . ' ' . $attribute->getValue()
            );
        }
    }

    /**
     * @param Attribute $other
     * @return boolean
     */
    public function equals(Attribute $other): bool
    {
        if ($this->name !== $other->getName()) {
            return false;
        }
        else if ($this->isBoolean !== $other->getIsBoolean()) {
            return false;
        }
        else if ($this->isBoolean) {
            return false;
        }
        else if ($other->getIsBoolean()) {
            return false;
        }
        else {
            return $this->value === $other->getValue();
        }
    }
}