<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class ElementType
{
    /**
     * Void elements as per https://html.spec.whatwg.org/multipage/syntax.html#void-elements
     * 
     * @var array|string[]
     */
    private const VOID_ELEMENTS = [
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
     * Based on https://www.w3.org/TR/2011/WD-html5-20110525/syntax.html#elements-0
     * But more restrictive
     * 
     * @var string
     */
    private const PATTERN_TAG_NAME = '/^[a-z][-_a-z0-9]*$/';

    /**
     * @var string
     */
    private $tagName;

    /**
     * @param string $tagName
     * @throws InvariantException
     */
    private function __construct(
        string $tagName
    ) {
        $this->tagName = mb_strtolower($tagName);

        Invariant::check(
            (bool) preg_match(self::PATTERN_TAG_NAME, $this->tagName),
            sprintf(
                'Tag name "%s" must match pattern "%s"', 
                $this->tagName,
                self::PATTERN_TAG_NAME
            )
        );
    }

    /**
     * @param string $tagName
     * @return self
     * @throws InvariantException
     */
    public static function fromTagName(string $tagName): self
    {
        return new self($tagName);
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * @return boolean
     */
    public function getIsVoid(): bool
    {
        return in_array($this->tagName, self::VOID_ELEMENTS);
    }
}