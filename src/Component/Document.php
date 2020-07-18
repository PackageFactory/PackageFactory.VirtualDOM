<?php declare(strict_types=1);
namespace PackageFactory\KristlBol\Domain;

use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Document implements ComponentInterface
{
    /**
     * @var string
     */
    private $doctype;

    /**
     * @var Head
     */
    private $head;

    /**
     * @var Body
     */
    private $body;

    /**
     * @param string $doctype
     * @param Head $head
     * @param Body $body
     */
    public function __construct(
        string $doctype,
        Head $head,
        Body $body
    ) {
        $this->doctype = $doctype;
        $this->head = $head;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getDoctype(): string
    {
        return $this->doctype;
    }

    /**
     * @return Head
     */
    public function getHead(): Head
    {
        return $this->head;
    }

    /**
     * @return Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void
    {
        $visitor->onElement(
            Element::fromArray([
                'name' => 'html',
                'children' => [
                    $this->head,
                    $this->body
                ]
            ])
        );
    }
}