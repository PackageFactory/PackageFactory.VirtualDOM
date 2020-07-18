<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Component;

use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\Text;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Script implements ComponentInterface
{
    /**
     * @var null|string
     */
    private $type;

    /**
     * @var null|string
     */
    private $src;

    /**
     * @var null|string
     */
    private $content;

    /**
     * @param null|string $type
     * @param null|string $src
     * @param null|string $content
     */
    private function __construct(?string $type, ?string $src, ?string $content)
    {
        $this->type = $type;
        $this->src = $src;
        $this->content = $content;
    }

    /**
     * @param string $src
     * @return self
     */
    public static function javascript(string $src): self
    {
        return new self(null, $src, null);
    }

    /**
     * @param string $content
     * @return self
     */
    public static function javascriptInline(string $content): self
    {
        return new self(null, null, $content);
    }

    /**
     * @param string $content
     * @return self
     */
    public static function json(string $content): self
    {
        return new self('application/json', null, $content);
    }

    /**
     * @param string $content
     * @return self
     */
    public static function jsonLd(string $content): self
    {
        return new self('application/ld+json', null, $content);
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        if ($this->src !== null) {
            $attributes = ['type' => $this->type, 'src' => $this->src];
            $children = [Text::fromString('')];
        } elseif ($this->content !== null) {
            $attributes = ['type' => $this->type];
            $children = [Text::fromDangerouslyUnescapedString($this->content)];
        } else {
            throw new \RuntimeException('@TODO: Invalid script element');
        }

        $visitor->onElement(
            Element::fromArray([
                'name' => 'script',
                'attributes' => $attributes,
                'children' => $children
            ])
        );
    }
}