<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

use PackageFactory\VirtualDOM\Util\Escaper;

final class Text implements ComponentInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     */
    private function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param string $text
     * @return self
     */
    public static function fromString(string $string): self
    {
        return new self(Escaper::escapeTextNodeValue($string));
    }

    /**
     * @param string $dangerouslyUnescapedString
     * @return self
     */
    public static function fromDangerouslyUnescapedString(string $dangerouslyUnescapedString): self
    {
        return new self($dangerouslyUnescapedString);
    }

    /**
     * @param \XMLReader $reader
     * @return self
     */
    public static function fromXMLReader(\XMLReader $reader): self
    {
        if ($reader->nodeType === \XMLReader::TEXT) {
            return self::fromDangerouslyUnescapedString($reader->value);
        }

        throw TextCannotBeCreated::
            becauseEncounteredNodeTypeCannotBeHandled($reader->nodeType);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->text;
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        $visitor->onText($this);
    }
}