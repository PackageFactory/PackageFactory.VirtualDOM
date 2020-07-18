<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class Element implements ComponentInterface
{
    /**
     * @var ElementName
     */
    private $name;

    /**
     * @var Attributes
     */
    private $attributes;

    /**
     * @var Children
     */
    private $children;

    /**
     * @param ElementName $name
     * @param Attributes $attributes
     * @param Children $children
     */
    private function __construct(
        ElementName $name,
        Attributes $attributes,
        Children $children
    ) {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->children = $children;
    }

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        if (isset($array['name']) || (!is_string($array['name']) && !($array['name'] instanceof ElementName))) {
            if ($array['name'] instanceof ElementName) {
                $name = $array['name'];
            } elseif (is_string($array['name'])) {
                $name = ElementName::fromString($array['name']);
            } else {
                throw ElementCannotBeCreated
                    ::becauseSourceArrayProvidesNameOfInvalidType($array['name']);
            }
        } else {
            throw ElementCannotBeCreated
                ::becauseSourceArrayDoesNotProvideAName();
        }

        if (isset($array['attributes'])) {
            if ($array['attributes'] instanceof Attributes) {
                $attributes = $array['attributes'];
            } elseif (is_array($array['attributes'])) {
                $attributes = Attributes::fromArray($array['attributes']);
            } else {
                throw ElementCannotBeCreated
                    ::becauseSourceArrayProvidesAttributesOfInvalidType($array['attributes']);
            }
        } else {
            $attributes = Attributes::fromArray([]);
        }

        if (isset($array['children'])) {
            if ($array['children'] instanceof Children) {
                $children = $array['children'];
            } elseif (is_array($array['children'])) {
                $children = Children::fromArray($array['children']);
            } else {
                throw ElementCannotBeCreated
                    ::becauseSourceArrayProvidesChildrenOfInvalidType($array['children']);
            }
        } else {
            $children = Children::fromArray([]);
        }

        return new self($name, $attributes, $children);
    }

    /**
     * @return ElementName
     */
    public function getName(): ElementName
    {
        return $this->name;
    }

    /**
     * @param ElementName $name
     * @return self
     */
    public function withName(ElementName $name): self
    {
        return new self($name, $this->attributes, $this->children);
    }

    /**
     * @return Attribute
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
        return new self($this->name, $attributes, $this->children);
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
        return new self($this->name, $this->attributes, $children);
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        $visitor->onElement($this);
    }
}