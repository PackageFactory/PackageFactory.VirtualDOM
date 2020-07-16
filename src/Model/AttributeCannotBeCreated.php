<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class AttributeCannotBeCreated extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Attribute cannot be created: ' . $message);
    }

    /**
     * @return self
     */
    public static function becauseSourceArrayIsNotAssociative(): self
    {
        return new self('Attributes cannot be created from numeric arrays.');
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseValueMustbeIsNotOfTypeBooleanOrString($attemptedValue): self
    {
        return new self(
            sprintf(
                'Source data must be of type boolean or string. Got "%s" instead.',
                is_object($attemptedValue) ? get_class($attemptedValue) : gettype($attemptedValue)
            )
        );
    }
}