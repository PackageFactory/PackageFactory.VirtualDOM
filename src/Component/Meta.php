<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Component;

use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Meta implements ComponentInterface
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|string
     */
    private $httpEquiv;

    /**
     * @var null|string
     */
    private $charset;

    /**
     * @var null|string
     */
    private $itemprop;

    /**
     * @var null|string
     */
    private $content;

    /**
     * @param null|string $name
     * @param null|string $httpEquiv
     * @param null|string $charset
     * @param null|string $itemprop
     * @param null|string $content
     */
    private function __construct(
        ?string $name,
        ?string $httpEquiv,
        ?string $charset,
        ?string $itemprop,
        ?string $content
    ) {
        $this->name = $name;
        $this->httpEquiv = $httpEquiv;
        $this->charset = $charset;
        $this->itemprop = $itemprop;
        $this->content = $content;
    }

    public static function name(string $name, string $content): self
    {
        return new self($name, null, null, null, $content);
    }

    public static function viewport(string $content): self
    {
        return self::name('viewport', $content);
    }

    public static function httpEquiv(string $httpEquiv, string $content): self
    {
        return new self(null, $httpEquiv, null, null, $content);
    }

    public static function refresh(int $seconds, string $url): self 
    {
        return self::httpEquiv('refresh', sprintf('%s;url=%s', $seconds, $url));
    }

    public static function charset(string $charset): self
    {
        return new self(null, null, $charset, null, null);
    }

    public static function itemprop(string $itemprop, string $content): self
    {
        return new self(null, null, null, $itemprop, $content);
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        if ($this->name !== null) {
            $attributes = ['name' => $this->name, 'content' => $this->content];
        } elseif ($this->httpEquiv !== null) {
            $attributes = ['httpEquiv' => $this->httpEquiv, 'content' => $this->content];
        } elseif ($this->itemprop !== null) {
            $attributes = ['itemprop' => $this->itemprop, 'content' => $this->content];
        } elseif ($this->charset !== null) {
            $attributes = ['charset' => $this->charset];
        } else {
            throw new \RuntimeException('@TODO: Invalid meta element');
        }

        $visitor->onElement(
            Element::fromArray([
                'name' => 'meta',
                'attributes' => $attributes
            ])
        );
    }
}