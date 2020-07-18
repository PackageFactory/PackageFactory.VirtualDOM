<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Component;

use PackageFactory\VirtualDOM\Model\Children;
use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Link implements ComponentInterface
{
    /**
     * @var string
     */
    private $rel;

    /**
     * @var string
     */
    private $href;

    /**
     * @param string $rel
     * @param string $href
     */
    private function __construct(string $rel, string $href)
    {
        $this->rel = $rel;
        $this->href = $href;
    }

    /**
     * @param string $href
     * @return self
     */
    public static function stylesheet(string $href): self
    {
        return new self('stylesheet', $href);
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        $visitor->onElement(
            Element::fromArray([
                'name' => 'link',
                'attributes' => [
                    'rel' => $this->rel,
                    'href' => $this->href
                ]
            ])
        );
    }
}