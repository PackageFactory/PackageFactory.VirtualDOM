<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

final class Fragment implements ComponentInterface
{
    /**
     * @var Children
     */
    private $children;

    /**
     * @param Children $children
     */
    private function __construct(Children $children) 
    {
        $this->children = $children;
    }

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        return new self(
            Children::fromArray($array)
        );
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
        $visitor->onFragment($this);
    }
}