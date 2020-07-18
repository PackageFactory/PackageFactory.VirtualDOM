<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Mutation;

use PackageFactory\VirtualDOM\Model\Children;
use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\Fragment;
use PackageFactory\VirtualDOM\Model\Text;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Crawler implements VisitorInterface
{
    /**
     * @var callable(Element $element): Element $callback
     */
    private $callback;

    /**
     * @var null|ComponentInterface
     */
    private $result;

    /**
     * @param callable $callback
     */
    private function __construct(callable $callback)
    {
        $this->callback = $callback;
        $this->result = null;
    }

    /**
     * @param Element $element
     * @return void
     */
    public function onElement(Element $element): void
    {
        $callback = $this->callback;
        $this->result = $callback($element);

        $children = [];
        foreach ($this->result->getChildren() as $child) {
            $children[] = self::crawl($child, $this->callback);
        }

        $this->result = $this->result->withChildren(Children::fromArray($children));
    }

    /**
     * @param Fragment $fragment
     * @return void
     */
    public function onFragment(Fragment $fragment): void
    {
        $this->result = $fragment;

        $children = [];
        foreach ($this->result->getChildren() as $child) {
            $children[] = self::crawl($child, $this->callback);
        }

        $this->result = $this->result->withChildren(Children::fromArray($children));
    }

    /**
     * @param Text $text
     * @return void
     */
    public function onText(Text $text): void
    {
        $this->result = $text;
    }

    /**
     * @param ComponentInterface $component
     * @param callable $callback
     * @return null|ComponentInterface
     */
    public static function crawl(ComponentInterface $component, callable $callback): ?ComponentInterface
    {
        $visitor = new self($callback);
        $component->render($visitor);

        return $visitor->result;
    }
}