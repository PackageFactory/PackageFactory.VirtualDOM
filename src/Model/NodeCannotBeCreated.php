<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class NodeCannotBeCreated extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Node cannot be created: ' . $message);
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
    public static function becauseSourceArrayDoesNotProvideAType(): self
    {
        return new self('Source array must have a key "type".');
    }

    /**
     * @return self
     */
    public static function becauseSourceArrayForElementDoesNotProvideAName(): self
    {
        return new self(
            sprintf(
                'Source array for type "%s" must have a key "name" with a non-empty string value.',
                NodeType::ELEMENT
            )
        );
    }

}