<?php declare(strict_types=1);
namespace PackageFactory\KristlBol\Domain;

use PackageFactory\VirtualDOM\Attributes;
use PackageFactory\VirtualDOM\Element;
use PackageFactory\VirtualDOM\ElementType;
use PackageFactory\VirtualDOM\Node;
use PackageFactory\VirtualDOM\NodeList;
use PackageFactory\VirtualDOM\Rendering\RenderableInterface;

final class Head implements RenderableInterface
{
    /**
     * @var Attributes
     */
    private $attributes;

    /**
     * @var NodeList
     */
    private $children;

    /**
     * @param Attributes $attributes
     * @param NodeList $children
     */
    private function __construct(
        Attributes $attributes,
        NodeList $children
    ) {
        $this->attributes = $attributes;
        $this->children = $children;
    }

    /**
     * @return self
     */
    public static function empty(): self
    {
        return new self(
            Attributes::createEmpty(),
            NodeList::createEmpty()
        );
    }

    /**
     * @return Attributes
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }

    /**
     * @param Attributes $attributes
     * @return self
     */
    public function withAttributes(Attributes $attributes): self
    {
        return new self (
            $attributes,
            $this->children
        );
    }

    /**
     * @return NodeList
     */
    public function getChildren(): NodeList
    {
        return $this->children;
    }

    /**
     * @param NodeList $children
     * @return self
     */
    public function withChildren(NodeList $children): self
    {
        return new self(
            $this->attributes,
            $children
        );
    }

    /**
     * @return Node
     */
    public function getAsVirtualDOMNode(): Node
    {
        return Element::create(
            ElementType::fromTagName('head'),
            $this->attributes,
            $this->children
        );
    }
}