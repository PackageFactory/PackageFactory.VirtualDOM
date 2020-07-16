<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class AttributesOperationIsNotPermitted extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $operation, string $message)
    {
        parent::__construct('Attributes operation ' . $operation . ' is not permitted: ' . $message);
    }

    /**
     * @param string $operation
     * @param string $attributeName
     * @return self
     */
    public static function becauseAttributeDoesNotExist(string $operation, string $attributeName): self
    {
        return new self(
            $operation,
            sprintf(
                'Attribute with name "%s" does not exist.',
                $attributeName
            )
        );
    }

    /**
     * @param string $operation
     * @param string $attributeName
     * @return self
     */
    public static function becauseAttributeAlreadyExists(string $operation, string $attributeName): self
    {
        return new self(
            $operation,
            sprintf(
                'Attribute with name "%s" already exists.',
                $attributeName
            )
        );
    }
}