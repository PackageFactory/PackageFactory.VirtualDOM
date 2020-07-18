<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

use PackageFactory\VirtualDOM\Util\Stringifier;

final class AttributeName
{
    /**
     * Based on https://www.w3.org/TR/2011/WD-html5-20110525/syntax.html#attributes-0
     * But more restrictive
     *
     * @var string
     */
    private const PATTERN = '/^[a-z][-_a-z0-9]*$/';

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    private function __construct(string $name)
    {
        if (!preg_match(self::PATTERN, $name)) {
            throw AttributeNameIsInvalid::
                becauseItDoesNotMatchTheRequiredPattern($name, self::PATTERN);
        }

        $this->name = $name;
    }

    /**
     * @param string $string
     * @return self
     */
    public static function fromString(string $string): self
    {
        return new self($string);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}