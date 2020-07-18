<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class ElementName
{
    /**
     * Based on https://www.w3.org/TR/2011/WD-html5-20110525/syntax.html#elements-0
     * But more restrictive
     * 
     * @var string
     */
    private const PATTERN = '/^[a-z][-_a-z0-9]*$/';

    /**
     * Void elements as per https://html.spec.whatwg.org/multipage/syntax.html#void-elements
     * 
     * @var array|string[]
     */
    private const VOIDS = [
        'area', 
        'base', 
        'br', 
        'col', 
        'embed', 
        'hr', 
        'img', 
        'input', 
        'link', 
        'meta', 
        'param', 
        'source', 
        'track', 
        'wbr'
    ];

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
            throw ElementNameIsInvalid::
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
     * @return bool
     */
    public function isVoid(): bool
    {
        return in_array($this->name, self::VOIDS);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}

