<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class NodeList implements \IteratorAggregate, \Countable
{
    /**
     * @var array|Node[]
     */
    private $nodes;

    /**
     * @param Node ...$nodes
     */
    private function __construct(
        Node ...$nodes
    ) {
        $this->nodes = $nodes;
    }

    /**
     * @param Node ...$nodes
     * @return self
     */
    public static function create(Node ...$nodes): self
    {
        return new self(...$nodes);
    }

    /**
     * @param string $data
     * @return self
     */
    public static function fromString(string $data): self
    {
        return new self(
            Text::fromString($data)
        );
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        throw new \Exception('@TODO: NodeList::fromString is not implemented yet!');
    }

    /**
     * @return self
     */
    public static function createEmpty(): self
    {
        return new self();
    }

    /**
     * @return array|Node[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @param integer $index
     * @return Node|null
     */
    public function getNodeAtIndex(int $index): ?Node
    {
        if (isset($this->nodes[$index])) {
            return $this->nodes[$index];
        } 
        else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function getIsEmpty(): bool
    {
        return empty($this->nodes);
    }

    /**
     * @param Node $prependedNode
     * @return self
     * @throws InvariantException
     */
    public function withPrependedNode(Node $prependedNode): self
    {
        $nextNodes = [$prependedNode];
        foreach ($this->nodes as $node) {
            Invariant::check(
                $prependedNode !== $node,
                'Prepended node "%s" must not be already in NodeList',
                substr((string) $prependedNode, 0, 10)
            );

            $nextNodes[] = $node;
        }

        return new self(...$nextNodes);
    }

    /**
     * @param Node $appendedNode
     * @return self
     * @throws InvariantException
     */
    public function withAppendedNode(Node $appendedNode): self
    {
        $nextNodes = [];
        foreach ($this->nodes as $node) {
            Invariant::check(
                $appendedNode !== $node,
                'Appended node "%s" must not be already in NodeList',
                substr((string) $appendedNode, 0, 10)
            );

            $nextNodes[] = $node;
        }

        $nextNodes[] = $appendedNode;

        return new self(...$nextNodes);
    }

    /**
     * @return iterable|Node[]
     */
    public function getIterator(): iterable
    {
        foreach ($this->nodes as $node) {
            yield $node;
        }
    }

    /**
     * @return integer
     */
    public function count(): int
    {
        return count($this->nodes);
    }
}