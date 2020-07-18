<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class ElementCannotBeCreated extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Element cannot be created: ' . $message);
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseSourceDataIsNotOfTypeStringOrArray($attemptedValue): self
    {
        return new self(
            sprintf(
                'Source data must be of type string or array. Got "%s" instead.',
                is_object($attemptedValue) ? get_class($attemptedValue) : gettype($attemptedValue)
            )
        );
    }

    /**
     * @return self
     */
    public static function becauseSourceArrayDoesNotProvideAName(): self
    {
        return new self('Source array must have a key "name" with a non-empty string value.');
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseSourceArrayProvidesNameOfInvalidType($attemptedValue): self
    {
        return new self(
            sprintf(
                'Source data must provide name of type string or %s. Got "%s" instead.',
                ElementName::class,
                is_object($attemptedValue) ? get_class($attemptedValue) : gettype($attemptedValue)
            )
        );
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseSourceArrayProvidesAttributesOfInvalidType($attemptedValue): self
    {
        return new self(
            sprintf(
                'Source data may provide attributes of type string or %s. Got "%s" instead.',
                Attributes::class,
                is_object($attemptedValue) ? get_class($attemptedValue) : gettype($attemptedValue)
            )
        );
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseSourceArrayProvidesChildrenOfInvalidType($attemptedValue): self
    {
        return new self(
            sprintf(
                'Source data may provide children of type string or %s. Got "%s" instead.',
                Children::class,
                is_object($attemptedValue) ? get_class($attemptedValue) : gettype($attemptedValue)
            )
        );
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseEncounteredNodeTypeCannotBeHandled($attemptedValue): self
    {
        return new self(
            sprintf(
                'Only XML nodes of type ELEMENT or TEXT can be handled, but node of type "%s" was encountered.',
                $attemptedValue
            )
        );
    }

    /**
     * @return self
     */
    public static function becauseXMLReaderEndedUnexpectedly(): self
    {
        return new self('XMLReader ended unexpectedly.');
    }
}