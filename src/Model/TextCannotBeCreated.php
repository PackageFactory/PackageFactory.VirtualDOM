<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class TextCannotBeCreated extends \DomainException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct('Text cannot be created: ' . $message);
    }

    /**
     * @param mixed $attemptedValue
     * @return self
     */
    public static function becauseEncounteredNodeTypeCannotBeHandled($attemptedValue): self
    {
        return new self(
            sprintf(
                'Only XML nodes of type TEXT can be handled, but node of type "%s" was encountered.',
                $attemptedValue
            )
        );
    }
}