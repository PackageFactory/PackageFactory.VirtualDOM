<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Fragment extends Node
{
    /**
     * @var NodeList
     */
    private $children;

    /**
     * @param NodeList $children
     */
    private function __construct(
        NodeList $children
    ) {
        $this->children = $children;
    }

    /**
     * @param Node ...$nodes
     * @return self
     */
    public static function create(Node ...$nodes): self 
    {
        return new self(NodeList::create(...$nodes));
    }

    /**
     * @return NodeList
     */
    public function getChildren(): NodeList
    {
        return $this->children;
    }

    /**
     * @param Node ...$children
     * @return self
     */
    public function withChildren(Node ...$children): self
    {
        return new self(
            $this->elementType,
            $this->attributes,
            NodeList::create(...$children)
        );
    }
}