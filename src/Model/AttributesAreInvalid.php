<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class AttributesAreInvalid extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Attributes are invalid: ' . $message);
    }

    /**
     * @param string $attributeName
     * @return self
     */
    public static function becauseAttributeNameIsNotUnique(string $attributeName): self
    {
        return new self(
            sprintf(
                'Attribute name "%s" is not unique within attribute list.',
                $attributeName
            )
        );
    }
}