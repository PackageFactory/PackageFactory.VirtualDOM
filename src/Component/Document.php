<?php declare(strict_types=1);
namespace PackageFactory\KristlBol\Domain;

use PackageFactory\VirtualDOM\Attribute;
use PackageFactory\VirtualDOM\Attributes;
use PackageFactory\VirtualDOM\Element;
use PackageFactory\VirtualDOM\ElementType;
use PackageFactory\VirtualDOM\Node;
use PackageFactory\VirtualDOM\NodeList;
use PackageFactory\VirtualDOM\Rendering\RenderableInterface;

final class Document implements RenderableInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $doctype;

    /**
     * @var Attributes
     */
    private $attributes;

    /**
     * @var Head
     */
    private $head;

    /**
     * @var Body
     */
    private $body;

    /**
     * @param Attributes $attributes
     * @param Head $head
     * @param Body $body
     */
    private function __construct(
        string $path,
        string $doctype,
        Attributes $attributes,
        Head $head,
        Body $body
    ) {
        $this->path = $path;
        $this->doctype = $doctype;
        $this->attributes = $attributes;
        $this->head = $head;
        $this->body = $body;
    }

    /**
     * @param string $path
     * @param string $lang
     * @return self
     */
    public static function empty(string $path, string $lang = 'en'): self
    {
        return new self(
            $path,
            'html',
            Attributes::fromArray([
                Attribute::fromNameAndValue('lang', $lang)
            ]),
            Head::empty(),
            Body::empty()
        );
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return self
     */
    public function withPath(string $path): self
    {
        return new self($path, $this->doctype, $this->attributes, $this->head, $this->body);
    }

    /**
     * @return string
     */
    public function getDoctype(): string
    {
        return $this->doctype;
    }

    /**
     * @param string $doctype
     * @return self
     */
    public function withDoctype(string $doctype): self
    {
        if ($doctype !== 'html') {
            throw new \Exception('Currently, there\'s no doctype allowed other than "html"');
        }

        return new self($this->path, $doctype, $this->attributes, $this->head, $this->body);
    }

    /**
     * @return Attributes
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
        return new self ($this->path, $this->doctype, $attributes, $this->head, $this->body);
    }

    /**
     * @return Head
     */
    public function getHead(): Head
    {
        return $this->head;
    }

    /**
     * @param Head $head
     * @return self
     */
    public function withHead(Head $head): self
    {
        return new self ($this->path, $this->doctype, $this->attributes, $head, $this->body);
    }

    /**
     * @return Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @param Body $body
     * @return self
     */
    public function withBody(Body $body): self
    {
        return new self ($this->path, $this->doctype, $this->attributes, $this->head, $body);
    }

    /**
     * @return Node
     */
    public function getAsVirtualDOMNode(): Node
    {
        return Element::create(
            ElementType::fromTagName('html'),
            $this->attributes,
            NodeList::create(
                $this->head->getAsVirtualDOMNode(),
                $this->body->getAsVirtualDOMNode()
            )
        );
    }
}