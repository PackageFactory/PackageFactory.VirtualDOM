<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Mutation;

use PackageFactory\VirtualDOM\Model\Attributes;
use PackageFactory\VirtualDOM\Model\Children;
use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\ElementName;
use PackageFactory\VirtualDOM\Model\Fragment;
use PackageFactory\VirtualDOM\Model\Text;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class Augmenter implements VisitorInterface
{
    /**
     * @var Element
     */
    private $result;

    /**
     * @param Attributes $attributes
     * @param ElementName|null $fallbackElementName
     */
    private function __construct(
        Attributes $attributes,
        ?ElementName $fallbackElementName = null
    ) {
        $this->result = Element::fromArray([
            'name' => $fallbackElementName ?? ElementName::fromString('div'), 
            'attributes' => $attributes
        ]);
    }

    /**
     * @param Element $element
     * @return void
     */
    public function onElement(Element $element): void
    {
        $this->result = $element->withAttributes(
            $element->getAttributes()->withDeeplyMergedAttributes(
                $this->result->getAttributes()
            )
        );
    }

    /**
     * @param Fragment $fragment
     * @return void
     */
    public function onFragment(Fragment $fragment): void
    {
        $this->result = $this->result->withChildren(
            $fragment->getChildren()
        );
    }

    /**
     * @param Text $text
     * @return void
     */
    public function onText(Text $text): void
    {
        $this->result = $this->result->withChildren(
            Children::fromArray([$text])
        );
    }

    /**
     * @param ComponentInterface $component
     * @param Attributes $attributes
     * @param null|ElementName $fallbackElementName
     * @return ComponentInterface
     */
    public static function augment(
        ComponentInterface $component,
        Attributes $attributes,
        ?ElementName $fallbackElementName = null
    ): ComponentInterface {
        $visitor = new self($attributes, $fallbackElementName);
        $component->render($visitor);

        return $visitor->result;
    }
}