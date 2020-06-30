<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Element extends Node
{
    /**
     * @var ElementType
     */
    private $elementType;

    /**
     * @var Attributes
     */
    private $attributes;

    /**
     * @var NodeList
     */
    private $children;

    /**
     * @param ElementType $elementType
     * @param Attributes $attributes
     * @param NodeList $children
     * @throws InvariantException
     */
    private function __construct(
        ElementType $elementType,
        Attributes $attributes,
        NodeList $children
    ) {
        $this->elementType = $elementType;
        $this->attributes = $attributes;
        $this->children = $children;

        if ($this->elementType->getIsVoid()) {
            Invariant::check(
                $this->children->getIsEmpty(),
                sprintf('Element <%s> must not have children', $this->elementType)
            );
        }
    }

    /**
     * @param ElementType $elementType
     * @param Attributes $attributes
     * @param NodeList $children
     * @return self
     * @throws InvariantException
     */
    public static function create(
        ElementType $elementType,
        Attributes $attributes,
        NodeList $children
    ): self {
        return new self(
            $elementType,
            $attributes,
            $children
        );
    }

    /**
     * @param array{
     *  tagName: string, 
     *  attributes?: array<string, string|bool>, 
     *  children?: (array|string[])|string
     * } $data
     * @return self
     */
    public function createFromShape(array $data): self
    {
        Invariant::check(
            isset($data['tagName']),
            'A "tagName" must be provided'
        );

        if (isset($data['attributes'])) {
            $attributes = Attributes::createFromArray($data['attributes']);
        }
        else {
            $attributes = Attributes::createEmpty();
        }

        if (isset($data['children']) && is_string($data['children'])) {
            $children = NodeList::createFromString($data['children']);
        }
        else if (isset($data['children']) && is_array($data['children'])) {
            $children = NodeList::createFromArray($data['children']);
        }
        else {
            $children = NodeList::createEmpty();
        }

        return new self(
            ElementType::createFromTagName($data['tagName']),
            $attributes,
            $children
        );
    }

    /**
     * @return ElementType
     */
    public function getElementType(): ElementType
    {
        return $this->elementType;
    }

    /**
     * @return Attributes
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }

    /**
     * @return NodeList
     */
    public function getChildren(): NodeList
    {
        return $this->children;
    }

    /**
     * @param Attributes $attributes
     * @return self
     */
    public function withAttributes(Attributes $attributes): self
    {
        return new self(
            $this->elementType,
            $attributes,
            $this->children
        );
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