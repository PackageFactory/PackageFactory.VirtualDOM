<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class Nodes implements \IteratorAggregate, \Countable
{
    /**
     * @var array|Node[]
     */
    private $nodes;

    /**
     * @param Node ...$nodes
     */
    private function __construct(Node ...$nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        $nodes = [];
        foreach ($array as $value) {
            if ($value instanceof Node) {
                $nodes[] = $value;
            } elseif (is_string($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $nodes[] = Node::text((string) $value);
            } elseif (is_array($value)) {
                $nodes[] = Node::fromArray($value);
            } else {
                throw NodeCannotBeCreated::
                    becauseSourceDataIsNotOfTypeStringOrArray($value);
            }
        }

        return new self(...$nodes);
    }

    /**
     * @return array|Node[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @param int $index
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
    public function isEmpty(): bool
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
        $nodes = array_merge([$prependedNode], $this->nodes);
        return new self(...$nodes);
    }

    /**
     * @param Nodes $prependedNodes
     * @return self
     * @throws InvariantException
     */
    public function withPrependedNodes(Nodes $prependedNodes): self
    {
        $nodes = array_merge($prependedNodes->getNodes(), $this->nodes);
        return new self(...$nodes);
    }

    /**
     * @param Node $appendedNode
     * @return self
     */
    public function withAppendedNode(Node $appendedNode): self
    {
        $nodes = $this->nodes;
        $nodes[] = $appendedNode;

        return new self(...$nodes);
    }

    /**
     * @param Nodes $appendedNodes
     * @return self
     */
    public function withAppendedNodes(Nodes $appendedNodes): self
    {
        $nodes = array_merge($this->nodes, $appendedNodes->getNodes());
        return new self(...$nodes);
    }

    /**
     * @return \Iterator<int, Node>
     */
    public function getIterator(): \Iterator
    {
        foreach ($this->nodes as $index => $node) {
            yield $index => $node;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->nodes);
    }
}