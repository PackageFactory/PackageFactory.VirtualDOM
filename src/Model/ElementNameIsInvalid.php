<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class ElementNameIsInvalid extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Element name is invalid: ' . $message);
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
                'Element name "%s" does not match required pattern "%s"',
                $attemptedName,
                $pattern
            )
        );
    }
}