<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

use PackageFactory\VirtualDOM\Escaper;

final class Node implements ComponentInterface
{
    /**
     * @var NodeType
     */
    private $type;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|Attributes
     */
    private $attributes;

    /**
     * @var null|Children
     */
    private $children;

    /**
     * @var null|string
     */
    private $value;

    /**
     * @param NodeType $type
     * @param null|string $name
     * @param null|Attributes $attributes
     * @param null|Nodes $children
     * @param null|string $value
     */
    private function __construct(
        NodeType $type, 
        ?string $name,
        ?Attributes $attributes,
        ?Nodes $children,
        ?string $value
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->attributes = $attributes;
        $this->children = $children;
        $this->value = $value;
    }

    public static function fromArray(array $array): self
    {
        if (empty($array['type']) || !is_string($array['type'])) {
            $type = NodeType::fromString($array['type']);

            switch ((string) $type) {
                default:
                case NodeType::ELEMENT: {
                    if (empty($array['name']) || !is_string($array['name'])) {
                        throw NodeCannotBeCreated
                            ::becauseSourceArrayForElementDoesNotProvideAName();
                    }

                    return self::element($array['name'], $array['attributes'] ?? [], $array['children'] ?? []);
                }

                case NodeType::FRAGMENT: 
                    return self::fragment($array['children'] ?? []);

                case NodeType::TEXT: 
                    return self::text($array['value'] ?? '');
            }
        } else {
            throw NodeCannotBeCreated
                ::becauseSourceArrayDoesNotProvideAType();
        }
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param array $children
     * @return self
     */
    public static function element(string $name, array $attributes = [], array $children = []): self
    {
        return new self(
            NodeType::element(), 
            $name, 
            Attributes::fromArray($attributes), 
            Nodes::fromArray($children), 
            null
        );
    }

    /**
     * @param array $children
     * @return self
     */
    public static function fragment(array $children = []): self
    {
        return new self(
            NodeType::fragment(),
            null, 
            null, 
            Nodes::fromArray($children), 
            null
        );
    }

    /**
     * @param string $text
     * @return self
     */
    public static function text(string $text): self
    {
        return new self(
            NodeType::text(),
            null,
            null,
            null,
            Escaper::escapeTextNodeValue($text)
        );
    }

    /**
     * @param string $dangerouslyUnescapedText
     * @return self
     */
    public static function dangerouslyUnescapedText(string $dangerouslyUnescapedText): self
    {
        return new self(
            NodeType::text(),
            null,
            null,
            null,
            $dangerouslyUnescapedText
        );
    }

    /**
     * @return NodeType
     */
    public function getType(): NodeType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if (!$this->type->isElement()) {
            throw new \BadMethodCallException;
        }

        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function withName(string $name): self
    {
        if (!$this->type->isElement() && !$this->type->isFragment()) {
            throw new \BadMethodCallException;
        }

        return new self(NodeType::element(), $name, $this->attributes, $this->children, $this->value);
    }

    /**
     * @return Attributes
     */
    public function getAttributes(): Attributes
    {
        if (!$this->type->isElement()) {
            throw new \BadMethodCallException;
        }

        /** @var Attributes $attributes */
        $attributes = $this->attributes;
        return $attributes;
    }

    /**
     * @param Attributes $attributes
     * @return self
     */
    public function withAttributes(Attributes $attributes): self
    {
        if (!$this->type->isElement()) {
            throw new \BadMethodCallException;
        }

        return new self($this->type, $this->name, $attributes, $this->children, $this->value);
    }

    /**
     * @return Nodes
     */
    public function getChildren(): Nodes
    {
        if (!$this->type->isElement() && !$this->type->isFragment()) {
            throw new \BadMethodCallException;
        }

        /** @var Nodes $children */
        $children = $this->children;
        return $children;
    }

    /**
     * @param Nodes $children
     * @return self
     */
    public function withChildren(Nodes $children): self
    {
        if (!$this->type->isElement() && !$this->type->isFragment()) {
            throw new \BadMethodCallException;
        }

        return new self($this->type, $this->name, $this->attributes, $children, $this->value);
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        if (!$this->type->isText()) {
            throw new \BadMethodCallException;
        }

        return $this->value;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withValue(string $value): self
    {
        if (!$this->type->isText()) {
            throw new \BadMethodCallException;
        }

        return new self($this->type, $this->name, $this->attributes, $this->children, $value);
    }

    /**
     * @return Node
     */
    public function render(): Node
    {
        return $this;
    }
}