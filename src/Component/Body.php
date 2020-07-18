<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Component;

use PackageFactory\VirtualDOM\Model\Children;
use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Body implements ComponentInterface
{
    /**
     * @var Children
     */
    private $children;

    /**
     * @param NodeList $children
     */
    private function __construct(Children $children) 
    {
        $this->children = $children;
    }

    /**
     * @return self
     */
    public static function empty(): self
    {
        return new self(Children::fromArray([]));
    }

    /**
     * @return Children
     */
    public function getChildren(): Children
    {
        return $this->children;
    }

    /**
     * @param Children $children
     * @return self
     */
    public function withChildren(Children $children): self
    {
        return new self(
            $this->attributes,
            $children
        );
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        $visitor->onElement(
            Element::fromArray([
                'name' => 'body',
                'children' => $this->children
            ])
        );
    }
}