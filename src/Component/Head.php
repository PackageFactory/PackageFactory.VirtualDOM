<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Component;

use PackageFactory\VirtualDOM\Component\Link;
use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Head implements ComponentInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var array|Meta[]
     */
    private $metas;

    /**
     * @var array|Link[]
     */
    private $links;

    /**
     * @var array|Script[]
     */
    private $scripts;

    /**
     * @param NodeList $children
     */
    private function __construct(string $title, array $metas, array $links, array $scripts) 
    {
        $this->title = $title;
        $this->metas = $metas;
        $this->links = $links;
        $this->scripts = $scripts;
    }

    /**
     * @return self
     */
    public static function fromTitle(string $title): self
    {
        return new self($title, [], [], []);
    }

    /**
     * @param Meta $meta
     * @return self
     */
    public function withMeta(Meta $meta): self
    {
        return new self($this->title, array_merge($this->metas, [$meta]), $this->links, $this->scripts);
    }

    /**
     * @param Link $link
     * @return self
     */
    public function withLink(Link $link): self
    {
        return new self($this->title, $this->metas, array_merge($this->links, [$link]), $this->scripts);
    }

    /**
     * @param Script $script
     * @return self
     */
    public function withScript(Script $script): self
    {
        return new self($this->title, $this->metas, $this->links, array_merge($this->scripts, [$script]));
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        $visitor->onElement(
            Element::fromArray([
                'name' => 'head',
                'children' => $this->children
            ])
        );
    }
}