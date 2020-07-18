<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class AttributeIsInvalid extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Attribute is invalid: ' . $message);
    }

    /**
     * @param string $name
     * @param string $attemptedValue
     * @return self
     */
    public static function becauseItsValueIsNeitherOfTypeStringNorBoolean(string $name, string $attemptedValue): self
    {
        return new self(
            sprintf(
                'Value of attribute "%s" must be of type string or boolean. Got "%s" instead.',
                $name,
                is_object($attemptedValue) ? get_class($attemptedValue) : gettype($attemptedValue)
            )
        );
    }
}