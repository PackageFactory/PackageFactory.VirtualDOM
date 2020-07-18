<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class Children implements \IteratorAggregate, \Countable
{
    /**
     * @var array|ComponentInterface[]
     */
    private $children;

    /**
     * @param ComponentInterface ...$children
     */
    private function __construct(ComponentInterface ...$children)
    {
        $this->children = $children;
    }

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        $children = [];
        foreach ($array as $value) {
            if ($value instanceof ComponentInterface) {
                $children[] = $value;
            } elseif (is_string($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $children[] = Text::fromString((string) $value);
            } elseif (is_array($value)) {
                $children[] = Element::fromArray($value);
            } else {
                throw ElementCannotBeCreated::
                    becauseSourceDataIsNotOfTypeStringOrArray($value);
            }
        }

        return new self(...$children);
    }

    /**
     * @param int $index
     * @return null|ComponentInterface
     */
    public function getChildAtIndex(int $index): ?ComponentInterface
    {
        if (isset($this->children[$index])) {
            return $this->children[$index];
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->children);
    }

    /**
     * @param Child $prependedChild
     * @return self
     * @throws InvariantException
     */
    public function withPrependedChild(ComponentInterface $prependedChild): self
    {
        $children = array_merge([$prependedChild], $this->children);
        return new self(...$children);
    }

    /**
     * @param Children $prependedChildren
     * @return self
     * @throws InvariantException
     */
    public function withPrependedChildren(Children $prependedChildren): self
    {
        $children = array_merge(iterator_to_array($prependedChildren), $this->children);
        return new self(...$children);
    }

    /**
     * @param ComponentInterface $appendedChild
     * @return self
     */
    public function withAppendedChild(ComponentInterface $appendedChild): self
    {
        $children = $this->children;
        $children[] = $appendedChild;

        return new self(...$children);
    }

    /**
     * @param Children $appendedChildren
     * @return self
     */
    public function withAppendedChildren(Children $appendedChildren): self
    {
        $children = array_merge($this->children, iterator_to_array($appendedChildren));
        return new self(...$children);
    }

    /**
     * @return \Iterator<int, ComponentInterface>
     */
    public function getIterator(): \Iterator
    {
        foreach ($this->children as $index => $node) {
            yield $index => $node;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->children);
    }
}