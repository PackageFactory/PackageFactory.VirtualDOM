<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class NodeTypeIsInvalid extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('NodeType is invalid: ' . $message);
    }

    /**
     * @param string $attemptedValue
     * @return self
     */
    public static function becauseItIsNotOneOfThePredefinedValues(string $attemptedValue): self
    {
        return new self(
            sprintf(
                'NodeType value must be one of "%s". Got "%s" instead.',
                join('", "', NodeType::getValues()),
                $attemptedValue
            )
        );
    }
}