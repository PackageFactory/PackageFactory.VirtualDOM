<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class AttributeNameIsInvalid extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Attribute name is invalid: ' . $message);
    }

    /**
     * @param string $attemptedName
     * @param string $pattern
     * @return self
     */
    public static function becauseItDoesNotMatchTheRequiredPattern(string $attemptedName, string $pattern): self
    {
        return new self(
            sprintf(
                'Attribute name "%s" does not match required pattern "%s"',
                $attemptedName,
                $pattern
            )
        );
    }
}