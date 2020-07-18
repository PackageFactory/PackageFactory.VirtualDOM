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
     * @param \XMLReader $reader
     * @return self
     */
    public static function fromXMLReader(\XMLReader $reader): self
    {
        $children = [];
        do {
            switch ($reader->nodeType) {
                case \XMLReader::ELEMENT: 
                    $children[] = Element::fromXMLReader($reader);
                    break;
                case \XMLReader::TEXT: 
                    $children[] =  Text::fromXMLReader($reader);
                    break;
                case \XMLReader::END_ELEMENT:
                    break 2;
                default: throw FragmentCannotBeCreated::
                    becauseEncounteredNodeTypeCannotBeHandled($reader->nodeType);
            }
        } while ($reader->read() !== false);

        return self::fromArray($children);
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
        return new self($children);
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